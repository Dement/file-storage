<?php

namespace BaseClasses;

use Doctrine\ORM\{
    EntityManager,
    Configuration,
    ORMException
};

use Doctrine\DBAL\Connection;
use Doctrine\Common\EventManager;

class BaseEntityManager extends EntityManager
{
    /**
     * Factory method to create EntityManager instances.
     *
     * @param mixed         $conn         An array with the connection parameters or an existing Connection instance.
     * @param Configuration $config       The Configuration instance to use.
     * @param EventManager  $eventManager The EventManager instance to use.
     *
     * @return EntityManager The created EntityManager.
     *
     * @throws \InvalidArgumentException
     * @throws ORMException
     */
    public static function create($conn, Configuration $config, EventManager $eventManager = null) : BaseEntityManager
    {
        if ( ! $config->getMetadataDriverImpl()) {
            throw ORMException::missingMappingDriverImpl();
        }

        switch (true) {
            case (is_array($conn)):
                $conn = \Doctrine\DBAL\DriverManager::getConnection(
                    $conn, $config, ($eventManager ?: new EventManager())
                );
                break;

            case ($conn instanceof Connection):
                if ($eventManager !== null && $conn->getEventManager() !== $eventManager) {
                    throw ORMException::mismatchedEventManager();
                }
                break;

            default:
                throw new \InvalidArgumentException("Invalid argument: " . $conn);
        }

        return new BaseEntityManager($conn, $config, $conn->getEventManager());
    }

    /**
     * {@inheritDoc}
     */
    public function createQueryBuilder() : BaseQueryBuilder
    {
        return new BaseQueryBuilder($this);
    }
}