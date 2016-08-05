<?php

namespace MongoSql\PHPSQLTree;

use MongoSql\MongoSqlException;

/**
 * Class AbstractParser
 * @package MongoSql\PHPSQLTree
 */
abstract class AbstractParser implements ParserInterface {

    /**
     * Get tree part by key with check
     *
     * @param array $sqlTree
     * @param $key
     * @param bool $strict
     * @return array|mixed
     * @throws MongoSqlException
     */
    protected function getTreePart(array &$sqlTree, $key, $strict = true) {
        $keyExists = array_key_exists($key, $sqlTree);
        if (!$keyExists && $strict)
            throw new MongoSqlException('Can\'t get ' . $key . ' part of the query.');

        return $keyExists ? $sqlTree[$key] : [];
    }

}