<?php

namespace Frontend\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductProperty
 *
 * @ORM\Table(name="product_property")
 * @ORM\Entity
 */
class ProductProperty
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
     * @ORM\Column(name="property_id", type="integer", nullable=false)
     */
    private $propertyId;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=200, nullable=false)
     */
    private $value;

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
     * @var \Frontend\ProductBundle\Entity\Property
     *
     * @ORM\ManyToOne(targetEntity="Frontend\ProductBundle\Entity\Property")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     * })
     */
    private $property;



    /**
     * Set productId
     *
     * @param integer $productId
     * @return ProductProperty
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
     * Set propertyId
     *
     * @param integer $propertyId
     * @return ProductProperty
     */
    public function setPropertyId($propertyId)
    {
        $this->propertyId = $propertyId;

        return $this;
    }

    /**
     * Get propertyId
     *
     * @return integer 
     */
    public function getPropertyId()
    {
        return $this->propertyId;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return ProductProperty
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
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
     * @return ProductProperty
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
     * Set property
     *
     * @param \Frontend\ProductBundle\Entity\Property $property
     * @return ProductProperty
     */
    public function setProperty(\Frontend\ProductBundle\Entity\Property $property = null)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return \Frontend\ProductBundle\Entity\Property 
     */
    public function getProperty()
    {
        return $this->property;
    }
}
