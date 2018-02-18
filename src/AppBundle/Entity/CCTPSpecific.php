<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CCTPSpecific
 *
 * @ORM\Table(name="cctp_Specific")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CCTPSpecificRepository")
 */
class CCTPSpecific
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CCTP", inversedBy="cctpSpecifics", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $cctp;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Projet", inversedBy="cctpSpecific", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $projet;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Fichier", mappedBy="cctpSpecific")
     */
    private $fichiers;


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
     * @return CCTPSpecific
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
     * @return CCTPSpecific
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
     * @return CCTPSpecific
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
     * Set projet
     *
     * @param \AppBundle\Entity\Projet $projet
     *
     * @return CCTPSpecific
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

    /**
     * Set cctp
     *
     * @param \AppBundle\Entity\CCTP $cctp
     *
     * @return CCTPSpecific
     */
    public function setCctp(\AppBundle\Entity\CCTP $cctp)
    {
        $this->cctp = $cctp;

        return $this;
    }

    /**
     * Get cctp
     *
     * @return \AppBundle\Entity\CCTP
     */
    public function getCctp()
    {
        return $this->cctp;
    }

    /**
     * Add fichier
     *
     * @param \AppBundle\Entity\Fichier $fichier
     *
     * @return CCTPSpecific
     */
    public function addFichier(\AppBundle\Entity\Fichier $fichier)
    {
        $this->fichiers[] = $fichier;

        return $this;
    }

    /**
     * Remove fichier
     *
     * @param \AppBundle\Entity\Fichier $fichier
     */
    public function removeFichier(\AppBundle\Entity\Fichier $fichier)
    {
        $this->fichiers->removeElement($fichier);
    }

    /**
     * Get fichiers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFichiers()
    {
        return $this->fichiers;
    }
}
