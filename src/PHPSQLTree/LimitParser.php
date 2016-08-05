<?php

namespace MongoSql\PHPSQLTree;

class LimitParser extends AbstractParser {

    /**
     * Parse tree and get limit or null
     *
     * @param array $sqlTree
     * @return int|null
     */
    public function parse(array &$sqlTree) {
        $limitPart = $this->getTreePart($sqlTree, 'LIMIT', false);

        if (empty($limitPart))
            return null;
        else
            return intval($limitPart['rowcount']);
    }
}