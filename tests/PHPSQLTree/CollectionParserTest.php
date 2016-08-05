<?php

namespace MongoSql\Tests\SqlTree;

use MongoSql\PHPSQLTree\CollectionParser;
use MongoSql\Tests\Base;

class CollectionParserTest extends Base {

    static $tree = [
        'FROM' => [
            [
                'expr_type' => 'table',
                'table' => 't'
            ]
        ]
    ];

    /**
     * Test getting colelction name
     */
    public function testParseSuccess() {
        $parser = new CollectionParser();
        $result = $parser->parse(self::$tree);

        $this->assertEquals('t', $result);
    }

    /**
     * Check if exception is thrown if FROM is not found
     *
     * @expectedException \MongoSql\MongoSqlException
     */
    public function testParseThrowsException() {
        $tree = [];
        $parser = new CollectionParser();
        $parser->parse($tree);
    }
}