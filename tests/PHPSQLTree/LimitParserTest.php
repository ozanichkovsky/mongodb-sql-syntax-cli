<?php

namespace MongoSql\Tests\SqlTree;

use MongoSql\Tests\Base;
use MongoSql\PHPSQLTree\LimitParser;

class LimitParserTest extends Base {

    static $limit = [
        'LIMIT' => [
            'rowcount' => '100'
        ]
    ];

    public function testParseSuccess() {
        $parser = new LimitParser();
        $result = $parser->parse(self::$limit);

        $this->assertEquals(100, $result);
    }

    public function testParseNull() {
        $empty = [];
        $parser = new LimitParser();
        $result = $parser->parse($empty);

        $this->assertNull($result);
    }
}