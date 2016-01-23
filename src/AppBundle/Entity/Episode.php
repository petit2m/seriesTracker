<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
* Actor
*
* @ORM\Table(name="episode")
* @ORM\Entity(repositoryClass="AppBundle\Entity\EpisodeRepository")
*/
class Episode
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
    * @ORM\Column(name="id_trakt", type="integer", nullable=false)
    */
    private $idTrakt;
 
    /**
    * @var integer
    *
    * @ORM\Column(name="number", type="integer", nullable=false)
    */
    private $number;
 
     /**
    * @var integer
    *
    * @ORM\Column(name="id_tvdb", type="integer", nullable=true)
    */
    private $idTvdb;
     
    /**
    * @var string
    *
    * @ORM\Column(name="title", type="string", length=150, nullable=false)
    */
    private $title;
    
    /**
    * @var string
    *
    * @ORM\Column(name="summary", type="text", nullable=true)
    */
    private $summary;
    
    /**
    * @var datetimetz
    *
    * @ORM\Column(name="aired", type="datetimetz", nullable=true)
    */
    private $aired;
        
    /**
    * @var datetimetz
    *
    * @ORM\Column(name="collected_at", type="datetimetz", nullable=true)
    */
    private $collectedAt;
        
    /**
    * @var datetimetz
    *
    * @ORM\Column(name="watchted_at", type="datetimetz", nullable=true)
    */
    private $watchedAt;
  
    /**
   * @var integer
   *
   * @ORM\Column(name="rating", type="float", nullable=true)
   */
   private $rating;

   /**
   * @var \season
   *
   * @ORM\ManyToOne(targetEntity="Season", inversedBy="episodes")
   */
   private $season;

   /**
   * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Image", cascade={"persist"})
   */
   private $images;   
  

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
     * Set idTrakt
     *
     * @param integer $idTrakt
     *
     * @return Episode
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
     * Set number
     *
     * @param integer $number
     *
     * @return Episode
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
     * Set idTvdb
     *
     * @param integer $idTvdb
     *
     * @return Episode
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Episode
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Episode
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set summary
     *
     * @param string $summary
     *
     * @return Episode
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
     * Set aired
     *
     * @param \DateTime $aired
     *
     * @return Episode
     */
    public function setAired($aired)
    {
        $this->aired = $aired;

        return $this;
    }

    /**
     * Get aired
     *
     * @return \DateTime
     */
    public function getAired()
    {
        return $this->aired;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     *
     * @return Episode
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
     * Set season
     *
     * @param \AppBundle\Entity\season $season
     *
     * @return Episode
     */
    public function setSeason(\AppBundle\Entity\season $season = null)
    {
        $this->season = $season;

        return $this;
    }

    /**
     * Get season
     *
     * @return \AppBundle\Entity\season
     */
    public function getSeason()
    {
        return $this->season;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add image
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Episode
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
     * Set collectedAt
     *
     * @param \DateTime $collectedAt
     *
     * @return Episode
     */
    public function setCollectedAt($collectedAt)
    {
        $this->collectedAt = $collectedAt;

        return $this;
    }

    /**
     * Get collectedAt
     *
     * @return \DateTime
     */
    public function getCollectedAt()
    {
        return $this->collectedAt;
    }

    /**
     * Set watchedAt
     *
     * @param \DateTime $watchedAt
     *
     * @return Episode
     */
    public function setWatchedAt($watchedAt)
    {
        $this->watchedAt = $watchedAt;

        return $this;
    }

    /**
     * Get watchedAt
     *
     * @return \DateTime
     */
    public function getWatchedAt()
    {
        return $this->watchedAt;
    }
    
    public function getImagesByTypeAndFormat($type, $format) {
        $newerCriteria = Criteria::create()
            ->where(Criteria::expr()->eq('type', $type))
                ->andWhere(Criteria::expr()->eq('format', $format));               

        return $this->getImages()->matching($newerCriteria);
    }
}
