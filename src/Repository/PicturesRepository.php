<?php

namespace App\Repository;


use App\Entity\Picture;
use App\Entity\Tag;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;


class PicturesRepository extends EntityRepository
{
    public function getPaginatedData($offset = 0, $limit = 0)
    {
        $query = $this->getEntityManager()->createQueryBuilder()->select('p')
            ->from(Picture::class, 'p')
            ->orderBy('p.id', 'desc')
            ->setMaxResults($limit)->setFirstResult($offset);
        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);

        return $paginator->getIterator()->getArrayCopy();
    }

    public function countAll()
    {
        $query = $this->getEntityManager()->createQueryBuilder()->select('count(p) as totalPictures')
            ->from(Picture::class, 'p');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getRandomPictureByTag(string $tag)
    {
        $rmsBuilder = new ResultSetMappingBuilder($this->getEntityManager());
        $rmsBuilder->addRootEntityFromClassMetadata(Picture::class, 'r1');

        return $this->getEntityManager()->createNativeQuery('SELECT r1.*
        FROM picture AS r1 JOIN
             (SELECT (RAND() *
                      (SELECT MAX(id)
                       FROM picture JOIN picture_tag pt on picture.id = pt.picture_id
                       WHERE pt.tag_name = :name)) AS id)
               AS r2
        JOIN picture_tag pt on r1.id = pt.picture_id
         WHERE r1.id >= r2.id and pt.tag_name = :name
        ORDER BY r1.id ASC
         LIMIT 1;', $rmsBuilder)->setParameters(['name' => $tag])->getSingleResult();

    }
}