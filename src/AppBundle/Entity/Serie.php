<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;

/**
* serie
*
* @ORM\Table(name="serie")
* @ORM\Entity(repositoryClass="AppBundle\Entity\SerieRepository")
*/
class Serie Extends IllustratedItem
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
    * @var integer
    *
    * @ORM\Column(name="year", type="smallint", nullable=true)
    */
    private $year;
    
    /**
    * @var string
    *
    * @ORM\Column(name="slug", type="string", length=150, nullable=true)
    */
    private $slug;

    /**
    * @var string
    *
    * @ORM\Column(name="title", type="string", length=150, nullable=false)
    */
    private $title;

    /**
    * @var text
    *
    * @ORM\Column(name="summary", type="text", nullable=true)
    */
    private $summary;

    /**
    * @var string
    *
    * @ORM\Column(name="network", type="string", length=50, nullable=true)
    */
    private $network;
    
    /**
    * @var datetimetz
    *
    * @ORM\Column(name="first_aired", type="datetimetz", nullable=true)
    */
    private $firstAired;
  
    /**
    * @var smallint
    *
    * @ORM\Column(name="runtime", type="smallint", nullable=true)
    */
    private $runtime;
    
    /**
    * @var datetimetz
    *
    * @ORM\Column(name="updated_at", type="datetimetz", nullable=true)
    */
    private $updatedAt;
    
    /**
    * @var string
    *
    * @ORM\Column(name="air_day", type="string", length=10, nullable=true)
    */
    private $airDay;
    
    /**
    * @var string
    *
    * @ORM\Column(name="air_time", type="string", length=5, nullable=true)
    */
    private $airTime;    
      

    /**
    * @var string
    *
    * @ORM\Column(name="status", type="string", length=20, nullable=true)
    */
    private $status;
    
    /**
    * @var float
    *
    * @ORM\Column(name="rating", type="float", nullable=true)
    */
    private $rating;
    
    /**
    * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Image", cascade={"persist"})
    */
    private $images;
    
    /**
    * @ORM\OneToMany(targetEntity="Season",mappedBy="serie",cascade={"persist"})
    */
    private $seasons;
    
    /**
      * @ORM\OneToMany(targetEntity="Person",mappedBy="serie",cascade={"persist"})
    */
    private $people; 
    
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
    * Set idTvdb
    *
    * @param integer $idTvdb
    *
    * @return Serie
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
    * @param integer $idTmdb
    *
    * @return Serie
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
    * Set slug
    *
    * @param string $slug
    *
    * @return Serie
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
    * Set name
    *
    * @param string $name
    *
    * @return Serie
    */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
    * Get name
    *
    * @return string
    */
    public function getName()
    {
        return $this->name;
    }

    /**
    * Set idTrackt
    *
    * @param integer $idTrackt
    *
    * @return Serie
    */
    public function setIdTrakt($idTrakt)
    {
        $this->idTrakt = $idTrakt;

        return $this;
    }

    /**
    * Get idTrackt
    *
    * @return integer
    */
    public function getIdTrakt()
    {
        return $this->idTrakt;
    }

    /**
    * Set title
    *
    * @param string $title
    *
    * @return Serie
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
    * @return Serie
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
    * Set year
    *
    * @param integer $year
    *
    * @return Serie
    */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
    * Get year
    *
    * @return integer
    */
    public function getYear()
    {
        return $this->year;
    }

    /**
    * Set network
    *
    * @param string $network
    *
    * @return Serie
    */
    public function setNetwork($network)
    {
        $this->network = $network;

        return $this;
    }

    /**
    * Get network
    *
    * @return string
    */
    public function getNetwork()
    {
        return $this->network;
    }

    /**
    * Set firstAired
    *
    * @param \DateTime $firstAired
    *
    * @return Serie
    */
    public function setFirstAired($firstAired)
    {
        $this->firstAired = $firstAired;

        return $this;
    }

    /**
    * Get firstAired
    *
    * @return \DateTime
    */
    public function getFirstAired()
    {
        return $this->firstAired;
    }

    /**
    * Set runtime
    *
    * @param integer $runtime
    *
    * @return Serie
    */
    public function setRuntime($runtime)
    {
        $this->runtime = $runtime;

        return $this;
    }

    /**
    * Get runtime
    *
    * @return integer
    */
    public function getRuntime()
    {
        return $this->runtime;
    }

    /**
    * Set updatedAt
    *
    * @param \DateTime $updatedAt
    *
    * @return Serie
    */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
    * Get updatedAt
    *
    * @return \DateTime
    */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
    * Set status
    *
    * @param string $status
    *
    * @return Serie
    */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
    * Get status
    *
    * @return string
    */
    public function getStatus()
    {
        return $this->status;
    }

    /**
    * Set rating
    *
    * @param integer $rating
    *
    * @return Serie
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
    * @return Serie
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
     * Set airDay
     *
     * @param string $airDay
     *
     * @return Serie
     */
    public function setAirDay($airDay)
    {
        $this->airDay = $airDay;

        return $this;
    }

    /**
     * Get airDay
     *
     * @return string
     */
    public function getAirDay()
    {
        return $this->airDay;
    }

    /**
     * Set airTime
     *
     * @param string $airTime
     *
     * @return Serie
     */
    public function setAirTime($airTime)
    {
        $this->airTime = $airTime;

        return $this;
    }

    /**
     * Get airTime
     *
     * @return string
     */
    public function getAirTime()
    {
        return $this->airTime;
    }

    /**
     * Set seasons
     *
     * @param \AppBundle\Entity\Season $seasons
     *
     * @return Serie
     */
    public function setSeasons(\AppBundle\Entity\Season $seasons = null)
    {
        $this->seasons = $seasons;

        return $this;
    }

    /**
     * Get seasons
     *
     * @return \AppBundle\Entity\Season
     */
    public function getSeasons()
    {
        return $this->seasons;
    }

    /**
     * Add season
     *
     * @param \AppBundle\Entity\Season $season
     *
     * @return Serie
     */
    public function addSeason(\AppBundle\Entity\Season $season)
    {
        $this->seasons[] = $season;
        $season->setSerie($this);
        
        return $this;
    }

    /**
     * Remove season
     *
     * @param \AppBundle\Entity\Season $season
     */
    public function removeSeason(\AppBundle\Entity\Season $season)
    {
        $this->seasons->removeElement($season);
    }

    /**
     * Add person
     *
     * @param \AppBundle\Entity\Character $person
     *
     * @return Serie
     */
    public function addPerson(\AppBundle\Entity\Person $person)
    {
        $this->people[] = $person;
        $person->setSerie($this);
        
        return $this;
    }

    /**
     * Remove person
     *
     * @param \AppBundle\Entity\Character $person
     */
    public function removePerson(\AppBundle\Entity\Person $person)
    {
        $this->people->removeElement($person);
        $person->removeSerie($this);
    }

    /**
     * Get people
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeople()
    {
        return $this->people;
    }
}
