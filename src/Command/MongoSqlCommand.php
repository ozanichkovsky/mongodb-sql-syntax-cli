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

        while (true) {
            $question = new Question("Enter SQL. Type 'exit' to quit:\n");

            $query = $helper->ask($input, $output, $question);

            if (trim(strtolower($query)) == 'exit')
                break;

            try {
                $params = $this->sqlService->parse($query);

                $result = $this->runnerService->execute($params);

                // Show result
                foreach ($result as $value) {
                    ob_start();
                    print_r($value);
                    $printResult = ob_get_clean();
                    $output->write($printResult);
                }
            } catch (MongoSqlException $ex) {
                $this->logger->error($ex->getMessage());
                $output->writeln('Error: ' . $ex->getMessage() . "\n");
            } catch (\Exception $ex) {
                $this->logger->critical($ex->getMessage());
            }
        }
    }
}