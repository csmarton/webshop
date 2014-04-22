<?php

namespace Frontend\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoryRelationship
 *
 * @ORM\Table(name="category_relationship")
 * @ORM\Entity
 */
class CategoryRelationship
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
     * @var integer
     *
     * @ORM\Column(name="first_category_id", type="integer", nullable=false)
     */
    private $firstCategoryId;

    /**
     * @var integer
     *
     * @ORM\Column(name="second_category_id", type="integer", nullable=false)
     */
    private $secondCategoryId;


    /**
     * @var \Frontend\ProductBundle\Entity\Category
     */
    private $firstCategory;

    /**
     * @var \Frontend\ProductBundle\Entity\Category
     */
    private $secondCategory;


    /**
     * Set firstCategoryId
     *
     * @param integer $firstCategoryId
     * @return CategoryRelationship
     */
    public function setFirstCategoryId($firstCategoryId)
    {
        $this->firstCategoryId = $firstCategoryId;

        return $this;
    }

    /**
     * Get firstCategoryId
     *
     * @return integer 
     */
    public function getFirstCategoryId()
    {
        return $this->firstCategoryId;
    }

    /**
     * Set secondCategoryId
     *
     * @param integer $secondCategoryId
     * @return CategoryRelationship
     */
    public function setSecondCategoryId($secondCategoryId)
    {
        $this->secondCategoryId = $secondCategoryId;

        return $this;
    }

    /**
     * Get secondCategoryId
     *
     * @return integer 
     */
    public function getSecondCategoryId()
    {
        return $this->secondCategoryId;
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
     * Set firstCategory
     *
     * @param \Frontend\ProductBundle\Entity\Category $firstCategory
     * @return CategoryRelationship
     */
    public function setFirstCategory(\Frontend\ProductBundle\Entity\Category $firstCategory = null)
    {
        $this->firstCategory = $firstCategory;

        return $this;
    }

    /**
     * Get firstCategory
     *
     * @return \Frontend\ProductBundle\Entity\Category 
     */
    public function getFirstCategory()
    {
        return $this->firstCategory;
    }

    /**
     * Set secondCategory
     *
     * @param \Frontend\ProductBundle\Entity\Category $secondCategory
     * @return CategoryRelationship
     */
    public function setSecondCategory(\Frontend\ProductBundle\Entity\Category $secondCategory = null)
    {
        $this->secondCategory = $secondCategory;

        return $this;
    }

    /**
     * Get secondCategory
     *
     * @return \Frontend\ProductBundle\Entity\Category 
     */
    public function getSecondCategory()
    {
        return $this->secondCategory;
    }
}
