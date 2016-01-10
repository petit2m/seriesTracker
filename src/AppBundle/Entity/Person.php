<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * person
 *
 * @ORM\Table(name="person")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PersonRepository")
 */
class Person
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
   * @var string
   *
   * @ORM\Column(name="name", type="string", length=100, nullable=false)
   */
  private $name;
  
  /**
    * @ORM\ManyToOne(targetEntity="Actor")
    */
  private $actor;


  /**
  * @ORM\ManyToOne(targetEntity="Serie", inversedBy="people")
  */
  private $serie;

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
     * Set name
     *
     * @param string $name
     *
     * @return Character
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
     * Set actor
     *
     * @param \AppBundle\Entity\Actor $actor
     *
     * @return Character
     */
    public function setActor(\AppBundle\Entity\Actor $actor = null)
    {
        $this->actor = $actor;

        return $this;
    }

    /**
     * Get actor
     *
     * @return \AppBundle\Entity\Actor
     */
    public function getActor()
    {
        return $this->actor;
    }

    /**
     * Set serie
     *
     * @param \AppBundle\Entity\Serie $serie
     *
     * @return Character
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
}
