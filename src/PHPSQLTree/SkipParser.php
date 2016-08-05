<?php

namespace MongoSql\PHPSQLTree;

class SkipParser extends AbstractParser{

    public function parse(array &$sqlTree) {
        $limitPart = $this->getTreePart($sqlTree, 'LIMIT', false);

        if (empty($limitPart))
            return null;
        else
            return intval($limitPart['offset']);
    }
}