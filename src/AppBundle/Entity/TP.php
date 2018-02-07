<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TP
 *
 * @ORM\Table(name="tp")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TPRepository")
 */
class TP
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TDR", mappedBy="tp")
     */
    private $tdrs;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CCTP", mappedBy="tp")
     */
    private $cctps;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Projet", cascade={"persist"}, mappedBy="tp")
     */
    private $projets;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Proc", cascade={"persist"}, mappedBy="tps")
     */
    private $procs;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime", nullable=true)
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
     * @return TP
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
     * @return TP
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
     * Constructor
     */
    public function __construct()
    {
        $this->tdrs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cctps = new \Doctrine\Common\Collections\ArrayCollection();
        $this->projets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->procs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dateCreation = new \DateTime('now');
    }

    /**
     * Add tdr
     *
     * @param \AppBundle\Entity\TDR $tdr
     *
     * @return TP
     */
    public function addTdr(\AppBundle\Entity\TDR $tdr)
    {
        $this->tdrs[] = $tdr;

        return $this;
    }

    /**
     * Remove tdr
     *
     * @param \AppBundle\Entity\TDR $tdr
     */
    public function removeTdr(\AppBundle\Entity\TDR $tdr)
    {
        $this->tdrs->removeElement($tdr);
    }

    /**
     * Get tdrs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTdrs()
    {
        return $this->tdrs;
    }

    /**
     * Add cctp
     *
     * @param \AppBundle\Entity\CCTP $cctp
     *
     * @return TP
     */
    public function addCctp(\AppBundle\Entity\CCTP $cctp)
    {
        $this->cctps[] = $cctp;

        return $this;
    }

    /**
     * Remove cctp
     *
     * @param \AppBundle\Entity\CCTP $cctp
     */
    public function removeCctp(\AppBundle\Entity\CCTP $cctp)
    {
        $this->cctps->removeElement($cctp);
    }

    /**
     * Get cctps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCctps()
    {
        return $this->cctps;
    }

    /**
     * Add proc
     *
     * @param \AppBundle\Entity\Proc $proc
     *
     * @return TP
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
     * Add projet
     *
     * @param \AppBundle\Entity\Projet $projet
     *
     * @return TP
     */
    public function addProjet(\AppBundle\Entity\Projet $projet)
    {
        $this->projets[] = $projet;

        return $this;
    }

    /**
     * Remove projet
     *
     * @param \AppBundle\Entity\Projet $projet
     */
    public function removeProjet(\AppBundle\Entity\Projet $projet)
    {
        $this->projets->removeElement($projet);
    }

    /**
     * Get projets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjets()
    {
        return $this->projets;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return TP
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
}
