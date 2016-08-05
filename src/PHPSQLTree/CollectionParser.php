<?php

namespace MongoSql\PHPSQLTree;

class CollectionParser extends AbstractParser{

    /**
     * Parse tree and get collection name
     *
     * @param array $sqlTree
     * @return string
     */
    public function parse(array &$sqlTree) {
        $fromPart = $this->getTreePart($sqlTree, 'FROM');
        foreach ($fromPart as $part) {
            if ($part['expr_type'] == 'table') {
                $table = $part['table'];
                break;
            }
        }

        return $table;
    }
}