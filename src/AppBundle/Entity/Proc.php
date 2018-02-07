<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proc
 *
 * @ORM\Table(name="proc")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProcRepository")
 */
class Proc
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
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\TP", cascade={"persist"}, inversedBy="procs")
     */
    private $tps;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\DAG", cascade={"persist"}, mappedBy="procs")
     */
    private $dags;

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
     * @return Proc
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
     * @return Proc
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
        $this->tps = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dateCreation = new \DateTime('now');
    }

    /**
     * Add tp
     *
     * @param \AppBundle\Entity\TP $tp
     *
     * @return Proc
     */
    public function addTp(\AppBundle\Entity\TP $tp)
    {
        $this->tps[] = $tp;

        return $this;
    }

    /**
     * Remove tp
     *
     * @param \AppBundle\Entity\TP $tp
     */
    public function removeTp(\AppBundle\Entity\TP $tp)
    {
        $this->tps->removeElement($tp);
    }

    /**
     * Get tps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTps()
    {
        return $this->tps;
    }

    /**
     * Add dag
     *
     * @param \AppBundle\Entity\DAG $dag
     *
     * @return Proc
     */
    public function addDag(\AppBundle\Entity\DAG $dag)
    {
        $this->dags[] = $dag;

        return $this;
    }

    /**
     * Remove dag
     *
     * @param \AppBundle\Entity\DAG $dag
     */
    public function removeDag(\AppBundle\Entity\DAG $dag)
    {
        $this->dags->removeElement($dag);
    }

    /**
     * Get dags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDags()
    {
        return $this->dags;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Proc
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
