<?php

namespace MongoSql\Tests\SqlTree;

use MongoSql\Tests\Base;
use MongoSql\PHPSQLTree\ProjectionParser;

class ProjectionParserTest extends Base {

    static $selectAll = [
        'SELECT' => [
            [
                'expr_type' => 'colref',
                'base_expr' => '*'
            ]
        ]
    ];

    static $selectFields = [
        'SELECT' => [
            [
                'expr_type' => 'colref',
                'base_expr' => 't.t.t'
            ],
            [
                'expr_type' => 'colref',
                'base_expr' => 'z.z.z'
            ],
        ]
    ];

    /**
     * Test getting null projection for select *
     */
    public function testParseSelectAllSuccess() {
        $parser = new ProjectionParser();
        $result = $parser->parse(self::$selectAll);

        $this->assertNull($result);
    }

    /**
     * Test parse fields
     */
    public function testParseSuccess() {
        $parser = new ProjectionParser();
        $result = $parser->parse(self::$selectFields);

        $expected = [
            't.t.t' => 1,
            'z.z.z' => 1
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * Check if exception is thrown if SELECT is not found
     *
     * @expectedException \MongoSql\MongoSqlException
     */
    public function testParseThrowsException() {
        $tree = [];
        $parser = new ProjectionParser();
        $parser->parse($tree);
    }
}