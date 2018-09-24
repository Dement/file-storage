<?php

namespace Modules\ObjectBundle\Repository;

use BaseClasses\BaseEntityManager;
use BaseClasses\BaseQueryBuilder;
use BaseClasses\BaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Modules\ObjectBundle\Entity\Objects;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query;

/**
 * ObjectRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 *
 * @method Objects getById($id)
 */
class ObjectsRepository extends BaseRepository
{
    /**
     * @param $etag
     * @return Objects
     */
    public function getByEtag($etag)
    {
        $query = $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.etag = :etag')
            ->setParameter('etag', $etag)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * @param $key
     * @return ArrayCollection
     */
    public function getListLikeByKey($key)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('t')
            ->where($qb->expr()->like('t.key', '?0'))
            ->setParameter(0, $key . '%')
            ->getQuery();

        return $this->getCollectionResult($qb->getQuery()->getResult());
    }

    /**
     * @param $key
     * @return ArrayCollection
     */
    public function getListLikeByFolder($key, $pos)
    {
        $em = $this->getEntityManager();

        $rsm = new Query\ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata('Modules\ObjectBundle\Entity\Objects', 'o');

        $sql = <<<SQL
        select {$rsm->generateSelectClause()} FROM objects o
        WHERE o.key like '{$key}%'
        and position('/' in substring(o.key from {$pos})) = 0
SQL;

        $query = $em->createNativeQuery($sql, $rsm);

        return $query->getResult();
    }

    /**
     * @param $key
     * @return bool
     */
    public function updateByKey($key)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->update()
            ->set('t.delete', ':delete')
            ->setParameter('delete', true);

        $qb->where($qb->expr()->like('t.key', '?0'))
            ->setParameter(0, $key . '%');

        $qb->getQuery()->execute();
    }
}