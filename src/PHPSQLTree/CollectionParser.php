<?php

namespace MongoSql\PHPSQLTree;

class CollectionParser extends AbstractParser{

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