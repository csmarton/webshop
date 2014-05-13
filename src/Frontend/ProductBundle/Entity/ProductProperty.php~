<?php

namespace Frontend\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductProperty
 * @ORM\Entity(repositoryClass="Frontend\ProductBundle\Repository\ProductPropertyRepository")
 * @ORM\Table(name="product_property", indexes={@ORM\Index(name="IDX_404276494584665A", columns={"product_id"}), @ORM\Index(name="IDX_40427649549213EC", columns={"property_id"})})
 */
class ProductProperty
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=200, nullable=false)
     */
    private $value;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * @var \Propertys
     *
     * @ORM\ManyToOne(targetEntity="Propertys")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     * })
     */
    private $property;



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
     * Set property
     *
     * @param \Frontend\ProductBundle\Entity\Propertys $property
     * @return ProductProperty
     */
    public function setProperty(\Frontend\ProductBundle\Entity\Propertys $property = null)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return \Frontend\ProductBundle\Entity\Propertys 
     */
    public function getProperty()
    {
        return $this->property;
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
}
