<?php

namespace MongoSql\Command;

use Knp\Command\Command;
use MongoSql\MongoSqlException;
use Monolog\Logger;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use MongoSql\Service\MongoRunnerService;
use MongoSql\Service\SqlToMongoService;

class MongoSqlCommand extends Command {

    /**
     * @var SqlToMongoService
     */
    private $sqlService;

    /**
     * @var MongoRunnerService
     */
    private $runnerService;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * Set Command services
     *
     * @param SqlToMongoService $sqlService
     * @param MongoRunnerService $runnerService
     * @param Logger $logger
     */
    public function setServices(SqlToMongoService $sqlService, MongoRunnerService $runnerService, Logger $logger) {
        $this->sqlService = $sqlService;
        $this->runnerService = $runnerService;
        $this->logger = $logger;
    }

    /**
     * Configure command
     */
    protected function configure() {
        $this
            ->setName('mongo:sql')
            ->setDescription('Execute MongoDB query using simple SQL syntax. Type word \'exit\' to quit console.');
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        do {
            $question = new Question("Enter SQL. Type 'exit' to quit:\n");

            $query = $helper->ask($input, $output, $question);

            try {
                $params = $this->sqlService->parse($query);

                $result = $this->runnerService->execute($params);

                // Show result
                foreach ($result as $value) {
                    print_r($value);
                }
            } catch (MongoSqlException $ex) {
                $this->monolog->error($ex->getMessage());
                echo("Error: " . $ex->getMessage() . "\n");
            } catch (Exception $ex) {
                $this->monolog->critical($ex->getMessage());
            }
        } while (trim(strtolower($query)) != 'exit');
    }
}