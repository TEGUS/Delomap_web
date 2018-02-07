<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DAG
 *
 * @ORM\Table(name="dag")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DAGRepository")
 */
class DAG
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
     * @var string
     *
     * @ORM\Column(name="dalaisTransmission", type="string", length=255, nullable=true)
     */
    private $dalaisTransmission;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Document", mappedBy="dag")
     */
    private $documents;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Proc", cascade={"persist"}, inversedBy="dags")
     */
    private $procs;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Fichier", mappedBy="dag")
     */
    private $fichiers;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime", unique=true)
     */
    private $dateCreation;

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
     * @return DAG
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
     * @return DAG
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
     * Set fichier
     *
     * @param string $fichier
     *
     * @return DAG
     */
    public function setFichier($fichier)
    {
        $this->fichier = $fichier;

        return $this;
    }

    /**
     * Get fichier
     *
     * @return string
     */
    public function getFichier()
    {
        return $this->fichier;
    }

    /**
     * Set dalaisTransmission
     *
     * @param string $dalaisTransmission
     *
     * @return DAG
     */
    public function setDalaisTransmission($dalaisTransmission)
    {
        $this->dalaisTransmission = $dalaisTransmission;

        return $this;
    }

    /**
     * Get dalaisTransmission
     *
     * @return string
     */
    public function getDalaisTransmission()
    {
        return $this->dalaisTransmission;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return DAG
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->procs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dateCreation = new \DateTime('now');
    }

    /**
     * Add document
     *
     * @param \AppBundle\Entity\Document $document
     *
     * @return DAG
     */
    public function addDocument(\AppBundle\Entity\Document $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove document
     *
     * @param \AppBundle\Entity\Document $document
     */
    public function removeDocument(\AppBundle\Entity\Document $document)
    {
        $this->documents->removeElement($document);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Add proc
     *
     * @param \AppBundle\Entity\Proc $proc
     *
     * @return DAG
     */
    public function addProc(\AppBundle\Entity\Proc $proc)
    {
        $this->procs[] = $proc;

        return $this;
    }

    /**
     * Remove proc
     *
     * @param \AppBundle\Entity\Proc $proc
     */
    public function removeProc(\AppBundle\Entity\Proc $proc)
    {
        $this->procs->removeElement($proc);
    }

    /**
     * Get procs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProcs()
    {
        return $this->procs;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return DAG
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
     * Add fichier
     *
     * @param \AppBundle\Entity\Fichier $fichier
     *
     * @return DAG
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
