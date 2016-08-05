<?php

namespace MongoSql\Tests\SqlTree;

use MongoSql\Tests\Base;
use MongoSql\PHPSQLTree\SortParser;

class SortParserTest extends Base {

    static $order = [
        'ORDER' => [
            [
                'expr_type' => 'colref',
                'base_expr' => 't.t.t',
                'direction' => 'ASC'
            ],
            [
                'expr_type' => 'colref',
                'base_expr' => 'z.z.z',
                'direction' => 'DESC'
            ]
        ]
    ];

    public function testParseSuccess() {
        $parser = new SortParser();
        $result = $parser->parse(self::$order);

        $expected = [
            't.t.t' => 1,
            'z.z.z' => -1
        ];

        $this->assertEquals($expected, $result);
    }

    public function testParseNull() {
        $empty = [];
        $parser = new SortParser();
        $result = $parser->parse($empty);

        $this->assertNull($result);
    }
}