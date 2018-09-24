<?php

namespace BaseCommand;

use BaseClasses\BaseContainerAwareCommand;
use Symfony\Component\Console\{
    Input\InputInterface,
    Output\OutputInterface
};

class ModeEnableUpdatesCommand extends BaseContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mode:enable:updates')
            ->setDescription('Enable mode updates');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if(!file_exists($this->getContainer()->get('kernel')->getRootDir() . '/../ModeEnable')) {
            fclose(fopen($this->getContainer()->get('kernel')->getRootDir() . '/../ModeEnable','x'));
        }
    }
}