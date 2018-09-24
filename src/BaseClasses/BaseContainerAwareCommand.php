<?php

namespace BaseClasses;

use Doctrine\Bundle\DoctrineBundle\Registry;
use \Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

abstract class BaseContainerAwareCommand extends ContainerAwareCommand
{
    /**
     * @return Registry
     */
    public function getDoctrine() : Registry
    {
        return $this->getContainer()->get('doctrine');
    }

    /**
     * @return ObjectManager
     */
    public function getManager() : ObjectManager
    {
        return $this->getDoctrine()->getManager();
    }
}