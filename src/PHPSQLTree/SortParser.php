<?php

namespace MongoSql\PHPSQLTree;

class SortParser extends AbstractParser{

    /**
     * Parse SQL tree and get sort array or null if sorting is not needed
     *
     * @param array $sqlTree
     * @return array|null
     */
    public function parse(array &$sqlTree) {
        $orderPart = $this->getTreePart($sqlTree, 'ORDER', false);

        if (empty($orderPart))
            return null;

        $result = [];

        foreach ($orderPart as $column) {
            if ($column['expr_type'] == 'colref') {
                $name = $column['base_expr'];
                $direction = $column['direction'];
                $result[$name] = $direction === 'ASC' ? 1 : -1;
            }
        }

        return $result;
    }
}