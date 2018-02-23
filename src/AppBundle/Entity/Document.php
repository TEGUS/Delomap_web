<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Document
 *
 * @ORM\Table(name="document")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DocumentRepository")
 */
class Document
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
     * @ORM\Column(name="dateUpload", type="date", nullable=true)
     */
    private $dateUpload;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateSignature", type="date", nullable=true)
     */
    private $dateSignature;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateSave", type="date")
     */
    private $dateSave;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Projet", inversedBy="documents", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $projet;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\DAG", inversedBy="documents", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $dag;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Fichier", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $fichierSigne;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Fichier", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $fichierModifie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime", nullable=true)
     */
    private $dateCreation;

    public function __construct()
    {
        $this->dateCreation = new \DateTime('now');
//        $this->dateSave = new \DateTime('now');
//        $this->dateUpload = new \DateTime('now');
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
     * Set dateUpload
     *
     * @param \DateTime $dateUpload
     *
     * @return Document
     */
    public function setDateUpload($dateUpload)
    {
        $this->dateUpload = $dateUpload;

        return $this;
    }

    /**
     * Get dateUpload
     *
     * @return \DateTime
     */
    public function getDateUpload()
    {
        return $this->dateUpload;
    }

    /**
     * Set dateSignature
     *
     * @param \DateTime $dateSignature
     *
     * @return Document
     */
    public function setDateSignature($dateSignature)
    {
        $this->dateSignature = $dateSignature;

        return $this;
    }

    /**
     * Get dateSignature
     *
     * @return \DateTime
     */
    public function getDateSignature()
    {
        return $this->dateSignature;
    }

    /**
     * Set dateSave
     *
     * @param \DateTime $dateSave
     *
     * @return Document
     */
    public function setDateSave($dateSave)
    {
        $this->dateSave = $dateSave;

        return $this;
    }

    /**
     * Get dateSave
     *
     * @return \DateTime
     */
    public function getDateSave()
    {
        return $this->dateSave;
    }

    /**
     * Set projet
     *
     * @param \AppBundle\Entity\Projet $projet
     *
     * @return Document
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
     * Set dag
     *
     * @param \AppBundle\Entity\DAG $dag
     *
     * @return Document
     */
    public function setDag(\AppBundle\Entity\DAG $dag)
    {
        $this->dag = $dag;

        return $this;
    }

    /**
     * Get dag
     *
     * @return \AppBundle\Entity\DAG
     */
    public function getDag()
    {
        return $this->dag;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Document
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
     * Set fichierSigne
     *
     * @param \AppBundle\Entity\Fichier $fichierSigne
     *
     * @return Document
     */
    public function setFichierSigne(\AppBundle\Entity\Fichier $fichierSigne = null)
    {
        $this->fichierSigne = $fichierSigne;

        return $this;
    }

    /**
     * Get fichierSigne
     *
     * @return \AppBundle\Entity\Fichier
     */
    public function getFichierSigne()
    {
        return $this->fichierSigne;
    }

    /**
     * Set fichierModifie
     *
     * @param \AppBundle\Entity\Fichier $fichierModifie
     *
     * @return Document
     */
    public function setFichierModifie(\AppBundle\Entity\Fichier $fichierModifie = null)
    {
        $this->fichierModifie = $fichierModifie;

        return $this;
    }

    /**
     * Get fichierModifie
     *
     * @return \AppBundle\Entity\Fichier
     */
    public function getFichierModifie()
    {
        return $this->fichierModifie;
    }
}
