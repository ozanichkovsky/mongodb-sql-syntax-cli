<?php

namespace MongoSql\Command;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class MongoSqlCommand extends Command {

    protected function configure() {
        $this
            ->setName('mongo:sql')
            ->setDescription('Execute MongoDB query using simple SQL syntax. Type word \'exit\' to quit console.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        do {
            $question = new Question("Enter SQL. Type 'exit' to quit:\n");

            $query = $helper->ask($input, $output, $question);

        } while (trim($query) != 'exit');
    }

}