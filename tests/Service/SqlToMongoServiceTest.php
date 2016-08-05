<?php

namespace MongoSql\Tests\Service;

use MongoSql\Parser\ParserFacadeInterface;
use MongoSql\Service\SqlToMongoService;
use MongoSql\Tests\Base;

class ParserFacadeFake implements ParserFacadeInterface {

    public function parse($sql) {

    }

    public function getCollectionName() {
        return 'test';
    }

    public function getLimit() {
        return 10;
    }

    public function getSkip() {
        return 10;
    }

    public function getProjection() {
        return ['a'];
    }

    public function getSort() {
        return ['a' => 1];
    }

    public function getQuery() {
        return [];
    }
}


class SqlToMongoServiceTest extends Base {


    public function testParse() {
        $sqlToMongoService = new SqlToMongoService(new ParserFacadeFake());

        $expected = [
            'collection' => 'test',
            'query' => [],
            'projection' => ['a'],
            'sort' => ['a' => 1],
            'limit' => 10,
            'skip' => 10
        ];

        $this->assertEquals($expected, $sqlToMongoService->parse('select 1'));
    }
}