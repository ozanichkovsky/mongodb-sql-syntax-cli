<?php

namespace MongoSql\Tests\Command;

use MongoSql\Command\MongoSqlCommand;
use MongoSql\MongoSqlException;
use MongoSql\Service\MongoRunnerService;
use MongoSql\Service\SqlToMongoService;
use MongoSql\Tests\Base;
use Monolog\Logger;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class MongoSqlCommandTest extends Base {

    private $sqlToMongoService;

    private $mongoRunnerService;

    private $logger;

    private $askHelper;

    protected function setUp() {
        $this->askHelper = $this->createMock(QuestionHelper::class);
        $this->askHelper
            ->expects($this->any())
            ->method('ask')
            ->will($this->onConsecutiveCalls('SELECT * FROM test', 'exit'));

        $this->logger = $this->createMock(Logger::class);

        $this->mongoRunnerService = $this->createMock(MongoRunnerService::class);

        $this->sqlToMongoService = $this->createMock(SqlToMongoService::class);
    }

    public function testThrowsMongoSqlException() {
        $this->sqlToMongoService
            ->expects($this->any())
            ->method('parse')
            ->willReturnCallback(function() { throw new MongoSqlException(); });

        $this->logger
            ->expects($this->once())
            ->method('error')
            ->willReturn(null);

        $app = $this->createApplication();

        /**
         * @var Application $console
         */
        $console = &$app["console"];
        $console->setAutoExit(false);
        $command = new MongoSqlCommand();
        $command->setServices($this->sqlToMongoService, $this->mongoRunnerService, $this->logger);
        $console->add($command);
        $command = $console->find('mongo:sql');
        $command->getHelperSet()->set($this->askHelper, 'question');

        $input = new ArrayInput(['mongo:sql']);
        $output = new NullOutput();
        $console->run($input, $output);
    }

    public function testThrowsException() {
        $this->sqlToMongoService
            ->expects($this->any())
            ->method('parse')
            ->willReturnCallback(function() { throw new \Exception(); });

        $this->logger
            ->expects($this->once())
            ->method('critical')
            ->willReturn(null);

        $app = $this->createApplication();

        /**
         * @var Application $console
         */
        $console = &$app["console"];
        $console->setAutoExit(false);
        $command = new MongoSqlCommand();
        $command->setServices($this->sqlToMongoService, $this->mongoRunnerService, $this->logger);
        $console->add($command);
        $command = $console->find('mongo:sql');
        $command->getHelperSet()->set($this->askHelper, 'question');

        $input = new ArrayInput(['mongo:sql']);
        $output = new NullOutput();
        $console->run($input, $output);
    }

    public function testPrintOutput() {
        $this->sqlToMongoService
            ->expects($this->any())
            ->method('parse')
            ->willReturn([]);

        $this->mongoRunnerService
            ->expects($this->once())
            ->method('execute')
            ->willReturn([[],[]]);

        $app = $this->createApplication();

        /**
         * @var Application $console
         */
        $console = &$app["console"];
        $console->setAutoExit(false);
        $command = new MongoSqlCommand();
        $command->setServices($this->sqlToMongoService, $this->mongoRunnerService, $this->logger);
        $console->add($command);
        $command = $console->find('mongo:sql');
        $command->getHelperSet()->set($this->askHelper, 'question');

        $input = new ArrayInput(['mongo:sql']);
        $output = new NullOutput();
        $console->run($input, $output);
    }
}