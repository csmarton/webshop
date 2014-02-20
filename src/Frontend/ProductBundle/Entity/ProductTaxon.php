<?php

namespace Frontend\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductTaxon
 *
 * @ORM\Table(name="product_taxon")
 * @ORM\Entity
 */
class ProductTaxon
{
    /**
     * @var integer
     *
     * @ORM\Column(name="product_id", type="integer", nullable=false)
     */
    private $productId;

    /**
     * @var integer
     *
     * @ORM\Column(name="taxon_id", type="integer", nullable=false)
     */
    private $taxonId;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Frontend\ProductBundle\Entity\Product
     *
     * @ORM\ManyToOne(targetEntity="Frontend\ProductBundle\Entity\Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * @var \Frontend\ProductBundle\Entity\Taxon
     *
     * @ORM\ManyToOne(targetEntity="Frontend\ProductBundle\Entity\Taxon")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="taxon_id", referencedColumnName="id")
     * })
     */
    private $taxon;



    /**
     * Set productId
     *
     * @param integer $productId
     * @return ProductTaxon
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return integer 
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set taxonId
     *
     * @param integer $taxonId
     * @return ProductTaxon
     */
    public function setTaxonId($taxonId)
    {
        $this->taxonId = $taxonId;

        return $this;
    }

    /**
     * Get taxonId
     *
     * @return integer 
     */
    public function getTaxonId()
    {
        return $this->taxonId;
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
     * Set product
     *
     * @param \Frontend\ProductBundle\Entity\Product $product
     * @return ProductTaxon
     */
    public function setProduct(\Frontend\ProductBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Frontend\ProductBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set taxon
     *
     * @param \Frontend\ProductBundle\Entity\Taxon $taxon
     * @return ProductTaxon
     */
    public function setTaxon(\Frontend\ProductBundle\Entity\Taxon $taxon = null)
    {
        $this->taxon = $taxon;

        return $this;
    }

    /**
     * Get taxon
     *
     * @return \Frontend\ProductBundle\Entity\Taxon 
     */
    public function getTaxon()
    {
        return $this->taxon;
    }
}
