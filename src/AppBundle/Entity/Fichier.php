<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Fichier
 *
 * @ORM\Table(name="fichier")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FichierRepository")
 * @Vich\Uploadable
 */
class Fichier
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @Vich\UploadableField(mapping="docs_a_generer", fileNameProperty="nom")
     * @var File
     */
    private $docFile;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\DAG", inversedBy="fichiers", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $dag;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CCTPSpecific", inversedBy="fichiers", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $cctpSpecific;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TDRSpecific", inversedBy="fichiers", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $tdrSpecific;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return Fichier
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Fichier
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Fichier
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setDocFile(File $doc = null)
    {
        $this->docFile = $doc;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($doc) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getDocFile()
    {
        return $this->docFile;
    }

    /**
     * Set dag
     *
     * @param \AppBundle\Entity\DAG $dag
     *
     * @return Fichier
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
     * Set cctpSpecific
     *
     * @param \AppBundle\Entity\CCTPSpecific $cctpSpecific
     *
     * @return Fichier
     */
    public function setCctpSpecific(\AppBundle\Entity\CCTPSpecific $cctpSpecific = null)
    {
        $this->cctpSpecific = $cctpSpecific;

        return $this;
    }

    /**
     * Get cctpSpecific
     *
     * @return \AppBundle\Entity\CCTPSpecific
     */
    public function getCctpSpecific()
    {
        return $this->cctpSpecific;
    }

    /**
     * Set tdrSpecific
     *
     * @param \AppBundle\Entity\TDRSpecific $tdrSpecific
     *
     * @return Fichier
     */
    public function setTdrSpecific(\AppBundle\Entity\TDRSpecific $tdrSpecific = null)
    {
        $this->tdrSpecific = $tdrSpecific;

        return $this;
    }

    /**
     * Get tdrSpecific
     *
     * @return \AppBundle\Entity\TDRSpecific
     */
    public function getTdrSpecific()
    {
        return $this->tdrSpecific;
    }
}
