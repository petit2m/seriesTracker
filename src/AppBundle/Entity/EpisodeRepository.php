<?php

namespace AppBundle\Entity;

/**
 * EpisodeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EpisodeRepository extends \Doctrine\ORM\EntityRepository
{
    public function findBySlugAndNumbers($slug,$SeasonNumber,$EpisodeNumber)
    {
        return $this->createQueryBuilder('episode')
            ->innerJoin('episode.season','season')    
            ->innerJoin('season.serie','serie')    
            ->where('serie.slug = :slug and season.number= :season_number and episode.number= :episode_number ')
            ->setParameters(array('slug' => $slug,'season_number' => $SeasonNumber, 'episode_number' => $EpisodeNumber)) 
            ->getQuery()
            ->getResult();
    }
}