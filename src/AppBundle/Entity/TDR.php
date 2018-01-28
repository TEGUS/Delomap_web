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
}