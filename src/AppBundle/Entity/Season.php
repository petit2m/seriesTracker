<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
* serie
*
* @ORM\Table(name="season")
* @ORM\Entity(repositoryClass="AppBundle\Entity\SeasonRepository")
*/
class Season Extends IllustratedItem
{
    /**
    * @var integer
    *
    * @ORM\Column(name="id", type="integer", nullable=false)
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="IDENTITY")
    */
    private $id;

    /**
    * @var integer
    *
    * @ORM\Column(name="number", type="integer", nullable=true)
    */
    private $number;

    /**
    * @var integer
    *
    * @ORM\Column(name="episode_count", type="integer", nullable=true)
    */
    private $episodeCount;

    /**
    * @var integer
    *
    * @ORM\Column(name="aired_episodes", type="integer", nullable=true)
    */
    private $airedEpisodes;

    /**
    * @var integer
    *
    * @ORM\Column(name="id_tvdb", type="integer", nullable=true)
    */
    private $idTvdb;

    /**
    * @var integer
    *
    * @ORM\Column(name="id_tmdb", type="integer", nullable=true)
    */
    private $idTmdb;

    /**
    * @var integer
    *
    * @ORM\Column(name="id_trakt", type="integer", nullable=true)
    */
    private $idTrakt;
    
    /**
    * @var text
    *
    * @ORM\Column(name="summary", type="text", nullable=true)
    */
    private $summary;
  
    /**
    * @var float
    *
    * @ORM\Column(name="rating", type="float", nullable=true)
    */
    private $rating;
    
    /**
    * @ORM\OneToMany(targetEntity="Episode",mappedBy="season",cascade={"persist"})
    */
    private $episodes;
    
    /**
    * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Image", cascade={"persist"})
    */
    private $images;   
   
    /**
    * @var \serie
    *
    * @ORM\ManyToOne(targetEntity="Serie", inversedBy="seasons")
    */
    private $serie;
 
    /**
    * Constructor
    */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
    * Get id
    *
    * @return integer
    */
    public function getId()
    {
        return $this->id;
    }

    /**
    * Set number
    *
    * @param integer $number
    *
    * @return Season
    */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
    * Get number
    *
    * @return integer
    */
    public function getNumber()
    {
        return $this->number;
    }

    /**
    * Set episodeCount
    *
    * @param integer $episodeCount
    *
    * @return Season
    */
    public function setEpisodeCount($episodeCount)
    {
        $this->episodeCount = $episodeCount;

        return $this;
    }

    /**
    * Get episodeCount
    *
    * @return integer
    */
    public function getEpisodeCount()
    {
        return $this->episodeCount;
    }

    /**
    * Set airedEpisode
    *
    * @param integer $airedEpisode
    *
    * @return Season
    */
    public function setAiredEpisode($airedEpisode)
    {
        $this->airedEpisode = $airedEpisode;

        return $this;
    }

    /**
    * Get airedEpisode
    *
    * @return integer
    */
    public function getAiredEpisode()
    {
        return $this->airedEpisode;
    }

    /**
    * Set idTvdb
    *
    * @param integer $idTvdb
    *
    * @return Season
    */
    public function setIdTvdb($idTvdb)
    {
        $this->idTvdb = $idTvdb;

        return $this;
    }

    /**
    * Get idTvdb
    *
    * @return integer
    */
    public function getIdTvdb()
    {
        return $this->idTvdb;
    }

    /**
    * Set idTmdb
    *
    * @param integer $idTvdb
    *
    * @return Season
    */
    public function setIdTmdb($idTmdb)
    {
        $this->idTmdb = $idTmdb;

        return $this;
    }

    /**
    * Get idTmdb
    *
    * @return integer
    */
    public function getIdTmdb()
    {
        return $this->idTmdb;
    }

    /**
    * Set idTrakt
    *
    * @param integer $idTrakt
    *
    * @return Season
    */
    public function setIdTrakt($idTrakt)
    {
        $this->idTrakt = $idTrakt;

        return $this;
    }

    /**
    * Get idTrakt
    *
    * @return integer
    */
    public function getIdTrakt()
    {
        return $this->idTrakt;
    }

    /**
    * Set summary
    *
    * @param string $summary
    *
    * @return Season
    */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
    * Get summary
    *
    * @return string
    */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
    * Set rating
    *
    * @param integer $rating
    *
    * @return Season
    */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
    * Get rating
    *
    * @return integer
    */
    public function getRating()
    {
        return $this->rating;
    }

    /**
    * Add image
    *
    * @param \AppBundle\Entity\Image $image
    *
    * @return Season
    */
    public function addImage(\AppBundle\Entity\Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
    * Remove image
    *
    * @param \AppBundle\Entity\Image $image
    */
    public function removeImage(\AppBundle\Entity\Image $image)
    {
        $this->images->removeElement($image);
    }

    /**
    * Get images
    *
    * @return \Doctrine\Common\Collections\Collection
    */
    public function getImages()
    {
        return $this->images;
    }

    /**
    * Set serie
    *
    * @param \AppBundle\Entity\Serie $serie
    *
    * @return Season
    */
    public function setSerie(\AppBundle\Entity\Serie $serie = null)
    {
        $this->serie = $serie;

        return $this;
    }

    /**
    * Get serie
    *
    * @return \AppBundle\Entity\Serie
    */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
    * Set airedEpisodes
    *
    * @param integer $airedEpisodes
    *
    * @return Season
    */
    public function setAiredEpisodes($airedEpisodes)
    {
        $this->airedEpisodes = $airedEpisodes;

        return $this;
    }

    /**
    * Get airedEpisodes
    *
    * @return integer
    */
    public function getAiredEpisodes()
    {
        return $this->airedEpisodes;
    }
 

    /**
     * Add episode
     *
     * @param \AppBundle\Entity\Episode $episode
     *
     * @return Season
     */
    public function addEpisode(\AppBundle\Entity\Episode $episode)
    {
        $this->episodes[] = $episode;
        $episode->setSeason($this);
        
        return $this;
    }

    /**
     * Remove episode
     *
     * @param \AppBundle\Entity\Episode $episode
     */
    public function removeEpisode(\AppBundle\Entity\Episode $episode)
    {
        $this->episodes->removeElement($episode);
    }

    /**
     * Get episodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEpisodes()
    {
        return $this->episodes;
    }
}
