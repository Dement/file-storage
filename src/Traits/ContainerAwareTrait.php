<?php

namespace Traits;

use BaseClasses\BaseKernel;
use Symfony\Component\DependencyInjection\Container;

trait ContainerAwareTrait
{
    /**
     * @return Container
     */
    protected static function getContainer() : Container
    {
        /** @var Container $container */
        $container = BaseKernel::get()->getContainer();
        return $container;
    }
}