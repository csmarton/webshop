<?php

namespace Frontend\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Taxon
 *
 * @ORM\Table(name="taxon")
 * @ORM\Entity
 */
class Taxon
{
    /**
     * @var integer
     *
     * @ORM\Column(name="taxonomy_id", type="integer", nullable=false)
     */
    private $taxonomyId;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=30, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=30, nullable=false)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=100, nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Frontend\ProductBundle\Entity\Taxonomy
     *
     * @ORM\ManyToOne(targetEntity="Frontend\ProductBundle\Entity\Taxonomy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="taxonomy_id", referencedColumnName="id")
     * })
     */
    private $taxonomy;



    /**
     * Set taxonomyId
     *
     * @param integer $taxonomyId
     * @return Taxon
     */
    public function setTaxonomyId($taxonomyId)
    {
        $this->taxonomyId = $taxonomyId;

        return $this;
    }

    /**
     * Get taxonomyId
     *
     * @return integer 
     */
    public function getTaxonomyId()
    {
        return $this->taxonomyId;
    }

    /**
     * Set parentId
     *
     * @param integer $parentId
     * @return Taxon
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer 
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Taxon
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Taxon
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Taxon
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set taxonomy
     *
     * @param \Frontend\ProductBundle\Entity\Taxonomy $taxonomy
     * @return Taxon
     */
    public function setTaxonomy(\Frontend\ProductBundle\Entity\Taxonomy $taxonomy = null)
    {
        $this->taxonomy = $taxonomy;

        return $this;
    }

    /**
     * Get taxonomy
     *
     * @return \Frontend\ProductBundle\Entity\Taxonomy 
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }
}
