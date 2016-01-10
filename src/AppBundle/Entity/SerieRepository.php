<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SerieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SerieRepository extends EntityRepository
{
    public function getMySerie()
    {
        return $this->createQueryBuilder('s')
            ->where('s.remaining > 0 and s.archived = 0')
            ->getQuery()
            ->getResult();
    }
    
    public function findAll()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.title')
            ->getQuery()
            ->getResult();
    }
    
    public function findExisting($id)
    {
        return $this->createQueryBuilder('s')
            ->where('s.idTrakt = :id')
            ->setParameters(array('id' => $id)) 
            ->getQuery()
            ->getResult();
    }
    
}
