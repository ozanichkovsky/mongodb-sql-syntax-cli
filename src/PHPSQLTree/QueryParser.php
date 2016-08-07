<?php

namespace MongoSql\PHPSQLTree;

class QueryParser extends AbstractParser {

    /**
     * Operator precendence. Higher number has higher priority
     */
    const PRECEDENCE = [
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
    const COMPARISON_OPERATORS = [
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
    const LOGICAL_OPERATORS = [
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
                self::PRECEDENCE[strtoupper($token['base_expr'])] < $minPrecedence) {
                break;
            }

            $precedence = self::PRECEDENCE[strtoupper($token['base_expr'])];

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
                        self::COMPARISON_OPERATORS[$op] => $atomRhs
                    ]];
                break;

            case 'OR':
            case 'AND':
                $result = [
                    self::LOGICAL_OPERATORS[$op] => [$atomLhs, $atomRhs]
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
                    $result = $this->getNumberValue($result);
                } elseif (strpos($result, "'") === 0) {
                    $result = $this->getStringValue($result);
                }
                break;
            case 'bracket_expression':
                $result = $this->compute($current['sub_tree']);
        }
        next($whereTree);
        return $result;
    }

    /**
     * Get number value from const atom
     *
     * @param $val
     * @return float|int
     */
    private function getNumberValue($val) {
        if (strpos($val, '.') === false) {
            $val = intval($val);
        }
        else {
            $val = floatval($val);
        }

        return $val;
    }

    /**
     * Get string value from atom by trimming "'" symbol
     *
     * @param $val
     * @return string
     */
    private function getStringValue($val) {
        return trim($val, "'");
    }
}