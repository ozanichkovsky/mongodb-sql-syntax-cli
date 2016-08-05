<?php

namespace MongoSql\PHPSQLTree;

class QueryParser extends AbstractParser {

    /**
     * Operator precendence. Higher number has higher priority
     */
    const precedence = [
        'OR' => 1,
        'XOR' => 2,
        'AND' => 3,
        '=' => 4,
        '>' => 4,
        '<' => 4,
        '>=' => 4,
        '<=' => 4,
        '<>' => 4,
        '!=' => 4,
    ];

    /**
     * Map of SQL comparison operators to MongoDB operators
     */
    const comparisonOperators = [
        '=' => '$eq',
        '>' => '$gt',
        '<' => '$lt',
        '>=' => '$gte',
        '<=' => '$lte',
        '<>' => '$ne',
        '!=' => '$ne'
    ];

    /**
     * Logical operators
     */
    const logicalOperators = [
        'NOT' => '$not',
        'AND' => '$and',
        'OR' => '$or'
    ];

    /**
     * Parse SQL AST tree and return query array
     *
     * @param array $sqlTree
     * @return array
     */
    public function parse(array &$sqlTree) {
        $wherePart = $this->getTreePart($sqlTree, 'WHERE', false);

        if (empty($wherePart))
            return [];

        return $this->compute($wherePart);
    }

    /**
     * Recursive descent parsing of the SQL tree
     *
     * @param array $whereTree
     * @param int $minPrecedence
     * @return array|float|int|string
     */
    private function compute(array &$whereTree, $minPrecedence = 1) {
        $atomLhs = $this->getAtom($whereTree);

        while (true) {
            $token = current($whereTree);

            if ($token === false ||  $token['expr_type'] != 'operator' ||
                self::precedence[strtoupper($token['base_expr'])] < $minPrecedence) {
                break;
            }

            $precedence = self::precedence[strtoupper($token['base_expr'])];

            $nextMinPrecendence = $precedence + 1;
            $op = strtoupper($token['base_expr']);
            next($whereTree);
            $atomRhs = $this->compute($whereTree, $nextMinPrecendence);

            $atomLhs = $this->computeOp($op, $atomLhs, $atomRhs);
        }

        return $atomLhs;
    }

    /**
     * Compute operator expression
     *
     * @param $op Operator
     * @param $atomLhs - left expression
     * @param $atomRhs - right expression
     * @return array
     */
    private function computeOp($op, $atomLhs, $atomRhs) {
        switch ($op) {
            case '=':
            case '<>':
            case '>':
            case '<':
            case '>=':
            case '<=':
                $result = [$atomLhs =>
                    [
                        self::comparisonOperators[$op] => $atomRhs
                    ]];
                break;

            case 'OR':
            case 'AND':
                $result = [
                    self::logicalOperators[$op] => [$atomLhs, $atomRhs]
                ];
                break;
            case 'XOR':
                $result = [
                    '$nor' => [
                        [
                            '$and' =>
                                [
                                    $atomLhs,
                                    $atomRhs
                                ]
                        ],
                        [
                            '$nor' =>
                                [
                                    $atomLhs,
                                    $atomRhs
                                ]
                        ]
                    ]
                ];
                break;
           }

        return $result;
    }

    /**
     * Get field, const or bracker expression
     *
     * @param array $whereTree
     * @return array|float|int|string
     */
    private function getAtom(array &$whereTree) {
        $current = current($whereTree);

        switch($current['expr_type']) {
            case 'colref':
                $result =  $current['base_expr'];
                break;
            case 'const':
                $result =  $current['base_expr'];
                if (strpos($result, "'") === false) {
                    if (strpos($result, '.') === false) {
                         $result = intval($result);
                    }
                    else {
                        $result = floatval($result);
                    }
                } elseif (strpos($result, "'") === 0) {
                    $result = substr($result, 1, strlen($result) - 2);
                }
                break;
            case 'bracket_expression':
                $result = $this->compute($current['sub_tree']);
        }
        next($whereTree);
        return $result;
    }
}