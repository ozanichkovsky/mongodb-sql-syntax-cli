<?php

namespace MongoSql\PHPSQLTree;

interface ParserInterface {

    /**
     * Parse SQL tree
     *
     * @param array $sqlTree
     * @return mixed
     */
    function parse(array &$sqlTree);

}