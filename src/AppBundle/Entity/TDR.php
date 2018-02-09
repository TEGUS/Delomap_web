<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TDR
 *
 * @ORM\Table(name="tdr")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TDRRepository")
 */
class TDR
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
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TP", inversedBy="tdrs", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $tp;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TDRSpecific", mappedBy="tdr")
     */
    private $tdrSpecifics;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CCTPSpecific", mappedBy="tdr")
     */
    private $cctpSpecifics;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime", nullable=true)
     */
    private $dateCreation;


    public function __construct()
    {
        $this->dateCreation = new \DateTime('now');
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return TDR
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return TDR
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set tp
     *
     * @param \AppBundle\Entity\TP $tp
     *
     * @return TDR
     */
    public function setTp(\AppBundle\Entity\TP $tp)
    {
        $this->tp = $tp;

        return $this;
    }

    /**
     * Get tp
     *
     * @return \AppBundle\Entity\TP
     */
    public function getTp()
    {
        return $this->tp;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return TDR
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
     * Add tdrSpecific
     *
     * @param \AppBundle\Entity\TDRSpecific $tdrSpecific
     *
     * @return TDR
     */
    public function addTdrSpecific(\AppBundle\Entity\TDRSpecific $tdrSpecific)
    {
        $this->tdrSpecifics[] = $tdrSpecific;

        return $this;
    }

    /**
     * Remove tdrSpecific
     *
     * @param \AppBundle\Entity\TDRSpecific $tdrSpecific
     */
    public function removeTdrSpecific(\AppBundle\Entity\TDRSpecific $tdrSpecific)
    {
        $this->tdrSpecifics->removeElement($tdrSpecific);
    }

    /**
     * Get tdrSpecifics
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTdrSpecifics()
    {
        return $this->tdrSpecifics;
    }

    /**
     * Add cctpSpecific
     *
     * @param \AppBundle\Entity\CCTPSpecific $cctpSpecific
     *
     * @return TDR
     */
    public function addCctpSpecific(\AppBundle\Entity\CCTPSpecific $cctpSpecific)
    {
        $this->cctpSpecifics[] = $cctpSpecific;

        return $this;
    }

    /**
     * Remove cctpSpecific
     *
     * @param \AppBundle\Entity\CCTPSpecific $cctpSpecific
     */
    public function removeCctpSpecific(\AppBundle\Entity\CCTPSpecific $cctpSpecific)
    {
        $this->cctpSpecifics->removeElement($cctpSpecific);
    }

    /**
     * Get cctpSpecifics
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCctpSpecifics()
    {
        return $this->cctpSpecifics;
    }
}
