<?php

namespace BaseClasses;

use BaseExceptions\ApiException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Container;

class CheckServerUpgrade {

    /** @var  Container */
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Checks the server for closing
     */
    public function onKernelRequest()
    {
        /** @var Request $request */
        $request = $this->container->get('request_stack')->getCurrentRequest();

        if($this->container->getParameter('close_ip') && !in_array($request->getClientIp(), $this->container->getParameter('allowed_ip'))) {
            throw  new ApiException('update_server', 426);
        }

        if(file_exists($this->container->get('kernel')->getRootDir() . '/../ModeEnable')) {
            throw  new ApiException('update_server', 426);
        }
    }
}