<?php

namespace MongoSql\Tests\Service;

use MongoDB\Client;
use MongoDB\Database;
use MongoDB\Collection;
use MongoSql\Tests\Base;
use MongoSql\Service\MongoRunnerService;

class MongoRunnerServiceTest extends Base {

    public function testExecute() {
        $colMock = $this->createMock(Collection::class);

        $colMock
            ->expects($this->any())
            ->method('find')
            ->willReturn([]);

        $dbMock = $this->createMock(Database::class);

        $dbMock
            ->expects($this->any())
            ->method('__get')
            ->willReturn($colMock);

        $clientMock = $this->createMock(Client::class);

        $clientMock
            ->expects($this->any())
            ->method('__get')
            ->willReturn($dbMock);

        $mongoRunnerService = new MongoRunnerService($clientMock, 'test');

        $params = [
            'collection' => 'col',
            'query' => []
        ];

        $this->assertEquals([], $mongoRunnerService->execute($params));
    }
}