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
}
