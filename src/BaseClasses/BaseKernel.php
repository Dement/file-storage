<?php

namespace BaseClasses;

use Symfony\Component\HttpKernel\Kernel;

class BaseKernel
{
    private static $classKernel;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    /**
     * Singleton to receive a copy of Kernel, single on request
     *
     * @return \AppKernel
     * @throws \Exception
     */
    public static function get() : Kernel
    {
        if (null === self::$classKernel) {
            //TODO грязный хак что бы работала консоль, тут должно быть Exception
//            throw new \Exception('The AppKernel Class is not installed', 500);
            global $kernel;

            if ('AppKernel' == get_class($kernel)) {
                return $kernel;
            }

            throw new \Exception("AppKernel not found");
        }

        return self::$classKernel;
    }

    /**
     * The method sets the Kernel. Strictly use to create, and nowhere else
     *
     * @param $kernel
     * @throws \Exception
     */
    public static function setKernel($kernel)
    {
        if(get_class($kernel) !== 'AppKernel') {
            throw new \Exception('The class must be of type AppKernel', 500);
        }

        if (null === self::$classKernel) {
            self::$classKernel = $kernel;
        }
    }

    /**
     * A method for destroying an instance Kernel. Strictly!!! Using current tests
     */
    public static function destroy()
    {
        self::$classKernel = null;
    }
}