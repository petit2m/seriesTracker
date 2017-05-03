<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;

/**
* Actor
*
* @ORM\Table(name="actor")
* @ORM\Entity
*/
class Actor extends IllustratedItem
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
    * @ORM\Column(name="id_tmdb", type="integer", nullable=true)
    */
    private $idTmdb;
 
    /**
    * @var string
    *
    * @ORM\Column(name="slug", type="string", length=150, nullable=false)
    */
    private $slug;

    /**
    * @var string
    *
    * @ORM\Column(name="name", type="string", length=100, nullable=false)
    */
    private $name;
    
    /**
    * @var text
    *
    * @ORM\Column(name="biography", type="text", nullable=true)
    */
    private $biography;
  
    /**
    * @var datetimetz
    *
    * @ORM\Column(name="birthday", type="datetimetz", nullable=false)
    */
    private $birthday;

    /**
    * @var datetimetz
    *
    * @ORM\Column(name="death", type="datetimetz", nullable=true)
    */  
    private $death;
  
    /**
    * @var string
    *
    * @ORM\Column(name="birthplace", type="string", nullable=true)
    */
    private $birthplace;
  
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Actor
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
     * Set biography
     *
     * @param string $biography
     *
     * @return Actor
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;

        return $this;
    }

    /**
     * Get biography
     *
     * @return string
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Actor
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set death
     *
     * @param \DateTime $death
     *
     * @return Actor
     */
    public function setDeath($death)
    {
        $this->death = $death;

        return $this;
    }

    /**
     * Get death
     *
     * @return \DateTime
     */
    public function getDeath()
    {
        return $this->death;
    }

    /**
     * Set birthplace
     *
     * @param \DateTime $birthplace
     *
     * @return Actor
     */
    public function setBirthplace($birthplace)
    {
        $this->birthplace = $birthplace;

        return $this;
    }

    /**
     * Get birthplace
     *
     * @return \DateTime
     */
    public function getBirthplace()
    {
        return $this->birthplace;
    }

    /**
     * Add image
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Actor
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
     * Set idTrakt
     *
     * @param integer $idTrakt
     *
     * @return Actor
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
     * Set name
     *
     * @param string $name
     *
     * @return Actor
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
     * Set id Tmdb
     *
     * @author Nicolas
     */
    public function setIdTmdb ($idTmdb)
    {
        $this->idTmdb = $idTmdb;
        
        return $this;
    }
    
    /**
     * Get id Tmdb
     *
     * @author Nicolas
     */
    public function getIdTmdb ()
    {
        return $this->idTmdb;
    }
}
