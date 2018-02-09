<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Projet
 *
 * @ORM\Table(name="projet")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjetRepository")
 */
class Projet
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
     * @ORM\Column(name="libelle", type="string", length=255, nullable=false)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="string", length=255, nullable=true)
     */
    private $montant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateLancement", type="date", nullable=true)
     */
    private $dateLancement;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAttribution", type="date", nullable=true)
     */
    private $dateAttribution;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateSignature", type="date", nullable=true)
     */
    private $dateSignature;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDemarrage", type="date", nullable=true)
     */
    private $dateDemarrage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateReception", type="date", nullable=true)
     */
    private $dateReception;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="motif", type="text", nullable=true)
     */
    private $motif;

    /**
     * @var string
     *
     * @ORM\Column(name="observation", type="text", nullable=true)
     */
    private $observation;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Lot", mappedBy="projet")
     */
    private $lots;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Document", mappedBy="projet")
     */
    private $documents;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Contractant", inversedBy="projets", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $contractant;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="projets", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TP", cascade={"persist"}, inversedBy="projets")
     */
    private $tp;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreationEnBD", type="datetime", nullable=true)
     */
    private $dateCreationEnBD;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TDRSpecific", mappedBy="projet")
     */
    private $tdrSpecifics;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CCTPSpecific", mappedBy="projet")
     */
    private $cctpSpecifics;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Proc", cascade={"persist"}, inversedBy="projets")
     */
    private $procs;

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
     * Set montant
     *
     * @param string $montant
     *
     * @return Projet
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return string
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set dateLancement
     *
     * @param \DateTime $dateLancement
     *
     * @return Projet
     */
    public function setDateLancement($dateLancement)
    {
        $this->dateLancement = $dateLancement;

        return $this;
    }

    /**
     * Get dateLancement
     *
     * @return \DateTime
     */
    public function getDateLancement()
    {
        return $this->dateLancement;
    }

    /**
     * Set dateAttribution
     *
     * @param \DateTime $dateAttribution
     *
     * @return Projet
     */
    public function setDateAttribution($dateAttribution)
    {
        $this->dateAttribution = $dateAttribution;

        return $this;
    }

    /**
     * Get dateAttribution
     *
     * @return \DateTime
     */
    public function getDateAttribution()
    {
        return $this->dateAttribution;
    }

    /**
     * Set dateSignature
     *
     * @param \DateTime $dateSignature
     *
     * @return Projet
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
     * Set dateDemarrage
     *
     * @param \DateTime $dateDemarrage
     *
     * @return Projet
     */
    public function setDateDemarrage($dateDemarrage)
    {
        $this->dateDemarrage = $dateDemarrage;

        return $this;
    }

    /**
     * Get dateDemarrage
     *
     * @return \DateTime
     */
    public function getDateDemarrage()
    {
        return $this->dateDemarrage;
    }

    /**
     * Set dateReception
     *
     * @param \DateTime $dateReception
     *
     * @return Projet
     */
    public function setDateReception($dateReception)
    {
        $this->dateReception = $dateReception;

        return $this;
    }

    /**
     * Get dateReception
     *
     * @return \DateTime
     */
    public function getDateReception()
    {
        return $this->dateReception;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Projet
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
     * Set motif
     *
     * @param string $motif
     *
     * @return Projet
     */
    public function setMotif($motif)
    {
        $this->motif = $motif;

        return $this;
    }

    /**
     * Get motif
     *
     * @return string
     */
    public function getMotif()
    {
        return $this->motif;
    }

    /**
     * Set observation
     *
     * @param string $observation
     *
     * @return Projet
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;

        return $this;
    }

    /**
     * Get observation
     *
     * @return string
     */
    public function getObservation()
    {
        return $this->observation;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lots = new \Doctrine\Common\Collections\ArrayCollection();
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tps = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dateCreation = new \DateTime('now');
    }

    /**
     * Add lot
     *
     * @param \AppBundle\Entity\Lot $lot
     *
     * @return Projet
     */
    public function addLot(\AppBundle\Entity\Lot $lot)
    {
        $this->lots[] = $lot;

        return $this;
    }

    /**
     * Remove lot
     *
     * @param \AppBundle\Entity\Lot $lot
     */
    public function removeLot(\AppBundle\Entity\Lot $lot)
    {
        $this->lots->removeElement($lot);
    }

    /**
     * Get lots
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLots()
    {
        return $this->lots;
    }

    /**
     * Add document
     *
     * @param \AppBundle\Entity\Document $document
     *
     * @return Projet
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
     * Set contractant
     *
     * @param \AppBundle\Entity\Contractant $contractant
     *
     * @return Projet
     */
    public function setContractant(\AppBundle\Entity\Contractant $contractant = null)
    {
        $this->contractant = $contractant;

        return $this;
    }

    /**
     * Get contractant
     *
     * @return \AppBundle\Entity\Contractant
     */
    public function getContractant()
    {
        return $this->contractant;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Projet
     */
    public function setUser(\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set tp
     *
     * @param \AppBundle\Entity\TP $tp
     *
     * @return Projet
     */
    public function setTp(\AppBundle\Entity\TP $tp = null)
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Projet
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
     * Set dateCreationEnBD
     *
     * @param \DateTime $dateCreationEnBD
     *
     * @return Projet
     */
    public function setDateCreationEnBD($dateCreationEnBD)
    {
        $this->dateCreationEnBD = $dateCreationEnBD;

        return $this;
    }

    /**
     * Get dateCreationEnBD
     *
     * @return \DateTime
     */
    public function getDateCreationEnBD()
    {
        return $this->dateCreationEnBD;
    }

    /**
     * Add tdrSpecific
     *
     * @param \AppBundle\Entity\TDRSpecific $tdrSpecific
     *
     * @return Projet
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
     * @return Projet
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

    /**
     * Add proc
     *
     * @param \AppBundle\Entity\Proc $proc
     *
     * @return Projet
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
}
