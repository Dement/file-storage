<?php

namespace BaseCommand;

use BaseClasses\BaseContainerAwareCommand;
use Symfony\Component\Console\{
    Input\ArrayInput,
    Input\InputInterface,
    Output\OutputInterface
};

class DropTestDatabaseCommand extends BaseContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('testdb:drop')
            ->setDescription('Drop test database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('doctrine:database:drop');
        $arguments = array(
            'command' => 'doctrine:database:drop',
            '--force' => true,
            '--if-exists' => true,
        );
        $input = new ArrayInput($arguments);
        $command->run($input, $output);
    }
}