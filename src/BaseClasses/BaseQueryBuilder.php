<?php

namespace BaseClasses;

use Doctrine\ORM\{
    Query,
    QueryBuilder
};

use Symfony\Component\HttpFoundation\Request;
use Traits\ContainerAwareTrait;

class BaseQueryBuilder extends QueryBuilder {

    use ContainerAwareTrait;

    protected $cacheKey = false;

    private $customQuery = true;

    /**
     * @param $customQuery
     * @return $this
     */
    public function setCustomQuery($customQuery)
    {
        $this->customQuery = $customQuery;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCacheKey() : bool
    {
        return $this->cacheKey;
    }

    /**
     * @param $cacheKey
     * @return $this
     */
    public function setCacheKey($cacheKey)
    {
        $this->cacheKey = $cacheKey;
        return $this;
    }

    /**
     * Constructs a Query instance from the current specifications of the builder.
     *
     * <code>
     *     $qb = $em->createQueryBuilder()
     *         ->select('u')
     *         ->from('User', 'u');
     *     $q = $qb->getQuery();
     *     $results = $q->execute();
     * </code>
     *
     * @return Query
     */
    public function getQuery()
    {
        $query = parent::getQuery();

        return $query;
    }

}