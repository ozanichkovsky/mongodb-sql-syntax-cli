<?php

namespace MongoSql\PHPSQLTree;

class ProjectionParser extends AbstractParser{

    /**
     * Parse SQL tree and get array of projections.
     * Or null if 'SELECT *' was used
     *
     * @param array $sqlTree
     * @return array|null
     */
    public function parse(array &$sqlTree) {
        $selectPart = $this->getTreePart($sqlTree, 'SELECT');

        if (count($selectPart) === 1 && $selectPart[0]['base_expr'] == '*')
            return null;

        $result = [];

        foreach ($selectPart as $parameter) {
            if ($parameter['expr_type'] == 'colref') {
                $result[$parameter['base_expr']] = 1;
            }
        }

        return $result;
    }
}