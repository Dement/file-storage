<?php

namespace BaseCommand;

use BaseClasses\BaseContainerAwareCommand;
use Symfony\Component\Console\{
    Input\ArrayInput,
    Input\InputInterface,
    Output\OutputInterface
};

class CreateTestDatabaseCommand extends BaseContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('testdb:create')
            ->setDescription('Create test database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('doctrine:database:create');
        $arguments = array(
            'command' => 'doctrine:database:create',
            '--env' => 'test',
        );
        $input = new ArrayInput($arguments);
        $command->run($input, $output);

        ////////////////////////////////////////////////////////////////////////

        $command = $this->getApplication()->find('doctrine:schema:create');
        $arguments = array(
            'command' => 'doctrine:schema:create',
            '--env' => 'test',
        );
        $input = new ArrayInput($arguments);
        $command->run($input, $output);

        ////////////////////////////////////////////////////////////////////////

        $command = $this->getApplication()->find('fixtures:load');
        $arguments = array(
            'command' => 'fixtures:load',
            '--env' => 'test',
            '--append'
        );
        $input = new ArrayInput($arguments);
        try {
            $command->run($input, $output);
        } catch (\Exception $e) {
            $output->setVerbosity(OutputInterface::VERBOSITY_NORMAL);
            $output->writeln(sprintf("<error>Error: %s</error>", $e->getMessage()));
        }
    }
}