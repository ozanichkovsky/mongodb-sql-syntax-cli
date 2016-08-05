<?php

namespace MongoSql\PHPSQLTree;

use MongoSql\MongoSqlException;

abstract class AbstractParser implements ParserInterface {

    protected function getTreePart(array &$sqlTree, $key, $strict = true) {
        $keyExists = array_key_exists($key, $sqlTree);
        if (!$keyExists && $strict)
            throw new MongoSqlException('Can\'t get ' . $key . ' part of the query.');

        return $keyExists ? $sqlTree[$key] : [];
    }

}