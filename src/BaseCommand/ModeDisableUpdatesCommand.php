<?php

namespace BaseCommand;

use BaseClasses\BaseContainerAwareCommand;
use Symfony\Component\Console\{
    Input\InputInterface,
    Output\OutputInterface
};

class ModeDisableUpdatesCommand extends BaseContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mode:disable:updates')
            ->setDescription('Disable mode updates');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if(file_exists($this->getContainer()->get('kernel')->getRootDir() . '/../ModeEnable')) {
            unlink($this->getContainer()->get('kernel')->getRootDir() . '/../ModeEnable');
        }
    }
}