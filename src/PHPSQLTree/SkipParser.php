<?php

namespace MongoSql\PHPSQLTree;

class SkipParser extends AbstractParser{

    /**
     * Parse SQL tree and get SKIP number or null
     *
     * @param array $sqlTree
     * @return int|null
     */
    public function parse(array &$sqlTree) {
        $limitPart = $this->getTreePart($sqlTree, 'LIMIT', false);

        if (empty($limitPart))
            return null;
        else
            return intval($limitPart['offset']);
    }
}