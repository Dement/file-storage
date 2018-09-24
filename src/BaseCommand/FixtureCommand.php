<?php

namespace BaseCommand;

use BaseClasses\BaseContainerAwareCommand;
use Symfony\Component\Console\{
    Input\InputArgument,
    Input\InputInterface,
    Output\OutputInterface
};

class FixtureCommand extends BaseContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fixtures:load')
            ->setDescription('Load fixtures')
            ->addArgument('bundle', InputArgument::OPTIONAL, 'Bundle name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bundleName = $input->getArgument("bundle");

        if (!is_null($bundleName)) {
            $fixturesPath[] = __DIR__ . "/../Modules/$bundleName/Resources/config/fixtures/";
        } else {
            $modulesList = scandir(__DIR__ . "/../Modules");
            foreach ($modulesList as $module) {
                if ($module == '.' || $module == '..') {
                    continue;
                }
                $fixturesPath[] = __DIR__ . "/../Modules/$module/Resources/config/fixtures/";
            }

        }

        $dir = [];
        foreach ($fixturesPath as $path) {
            if (file_exists($path)) {
                $dirs = scandir($path);
                foreach ($dirs as $d) {
                    $dir[] = $path.$d;
                }

            }
        }

        $em = $this->getManager();

        foreach ($dir as $element) {
            if (is_dir($element)) {
                continue;
            }

            if (pathinfo($element, PATHINFO_EXTENSION) == "yml") {
                $loader = new \Nelmio\Alice\Fixtures\Loader();

                $objects = $loader->load($element);

                foreach($objects as $ob) {
                    $em->persist($ob);
                }
            }
        }

        $em->flush();
    }
}