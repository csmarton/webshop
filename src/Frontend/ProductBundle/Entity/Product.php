<?php

namespace Frontend\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity
 */
class Product
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
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=100, nullable=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var float
     *
     * @ORM\Column(name="gross_salary", type="float", precision=10, scale=0, nullable=false)
     */
    private $grossSalary;

    /**
     * @var float
     *
     * @ORM\Column(name="net_salary", type="float", precision=10, scale=0, nullable=false)
     */
    private $netSalary;

    /**
     * @var integer
     *
     * @ORM\Column(name="category", type="integer", nullable=false)
     */
    private $category;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @var boolean
     *
     * @ORM\Column(name="in_stock", type="boolean", nullable=false)
     */
    private $inStock;


    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $productImages;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $productPropertys;

    /**
     * @var \Frontend\ProductBundle\Entity\Category
     */
    private $categorys;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productImages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->productPropertys = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Product
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
     * @return Product
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
     * @return Product
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Product
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Product
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

    /**
     * Set grossSalary
     *
     * @param float $grossSalary
     * @return Product
     */
    public function setGrossSalary($grossSalary)
    {
        $this->grossSalary = $grossSalary;

        return $this;
    }

    /**
     * Get grossSalary
     *
     * @return float 
     */
    public function getGrossSalary()
    {
        return $this->grossSalary;
    }

    /**
     * Set netSalary
     *
     * @param float $netSalary
     * @return Product
     */
    public function setNetSalary($netSalary)
    {
        $this->netSalary = $netSalary;

        return $this;
    }

    /**
     * Get netSalary
     *
     * @return float 
     */
    public function getNetSalary()
    {
        return $this->netSalary;
    }

    /**
     * Set category
     *
     * @param integer $category
     * @return Product
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return integer 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Product
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set inStock
     *
     * @param boolean $inStock
     * @return Product
     */
    public function setInStock($inStock)
    {
        $this->inStock = $inStock;

        return $this;
    }

    /**
     * Get inStock
     *
     * @return boolean 
     */
    public function getInStock()
    {
        return $this->inStock;
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
     * Add productImages
     *
     * @param \Frontend\ProductBundle\Entity\ProductImages $productImages
     * @return Product
     */
    public function addProductImage(\Frontend\ProductBundle\Entity\ProductImages $productImages)
    {
        $this->productImages[] = $productImages;

        return $this;
    }

    /**
     * Remove productImages
     *
     * @param \Frontend\ProductBundle\Entity\ProductImages $productImages
     */
    public function removeProductImage(\Frontend\ProductBundle\Entity\ProductImages $productImages)
    {
        $this->productImages->removeElement($productImages);
    }

    /**
     * Get productImages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductImages()
    {
        return $this->productImages;
    }

    /**
     * Add productPropertys
     *
     * @param \Frontend\ProductBundle\Entity\ProductProperty $productPropertys
     * @return Product
     */
    public function addProductProperty(\Frontend\ProductBundle\Entity\ProductProperty $productPropertys)
    {
        $this->productPropertys[] = $productPropertys;

        return $this;
    }

    /**
     * Remove productPropertys
     *
     * @param \Frontend\ProductBundle\Entity\ProductProperty $productPropertys
     */
    public function removeProductProperty(\Frontend\ProductBundle\Entity\ProductProperty $productPropertys)
    {
        $this->productPropertys->removeElement($productPropertys);
    }

    /**
     * Get productPropertys
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductPropertys()
    {
        return $this->productPropertys;
    }

    /**
     * Set categorys
     *
     * @param \Frontend\ProductBundle\Entity\Category $categorys
     * @return Product
     */
    public function setCategorys(\Frontend\ProductBundle\Entity\Category $categorys = null)
    {
        $this->categorys = $categorys;
        $this->category = $categorys->getId();
        return $this;
    }

    /**
     * Get categorys
     *
     * @return \Frontend\ProductBundle\Entity\Category 
     */
    public function getCategorys()
    {
        if($this->category == 0){
            return null;
        }else{
            return $this->categorys;
        }    
    }
}
