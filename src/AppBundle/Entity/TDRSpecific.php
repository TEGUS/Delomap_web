<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

/**
 * TDRSpecific
 *
 * @ORM\Table(name="t_d_r_specific")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TDRSpecificRepository")
 */
class TDRSpecific
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="service", type="text")
     */
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TDR", inversedBy="tdrSpecifics", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $tdr;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Projet", inversedBy="tdrSpecifics", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $projet;


    public function __construct()
    {
        $this->dateCreation = new Date('now');
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return TDRSpecific
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return TDRSpecific
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set service
     *
     * @param string $service
     *
     * @return TDRSpecific
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set tdr
     *
     * @param \AppBundle\Entity\TDR $tdr
     *
     * @return TDRSpecific
     */
    public function setTdr(\AppBundle\Entity\TDR $tdr)
    {
        $this->tdr = $tdr;

        return $this;
    }

    /**
     * Get tdr
     *
     * @return \AppBundle\Entity\TDR
     */
    public function getTdr()
    {
        return $this->tdr;
    }

    /**
     * Set projet
     *
     * @param \AppBundle\Entity\Projet $projet
     *
     * @return TDRSpecific
     */
    public function setProjet(\AppBundle\Entity\Projet $projet)
    {
        $this->projet = $projet;

        return $this;
    }

    /**
     * Get projet
     *
     * @return \AppBundle\Entity\Projet
     */
    public function getProjet()
    {
        return $this->projet;
    }
}
