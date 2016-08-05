<?php

namespace MongoSql\Tests\SqlTree;

use MongoSql\Tests\Base;
use MongoSql\PHPSQLTree\SkipParser;

class SkipParserTest extends Base {

    static $offset = [
        'LIMIT' => [
            'offset' => '100'
        ]
    ];

    public function testParseSuccess() {
        $parser = new SkipParser();
        $result = $parser->parse(self::$offset);

        $this->assertEquals(100, $result);
    }

    public function testParseNull() {
        $empty = [];
        $parser = new SkipParser();
        $result = $parser->parse($empty);

        $this->assertNull($result);
    }
}