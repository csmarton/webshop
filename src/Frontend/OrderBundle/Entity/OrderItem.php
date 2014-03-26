<?php

namespace Frontend\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderItem
 *
 * @ORM\Table(name="order_item")
 * @ORM\Entity
 */
class OrderItem
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
     * @ORM\Column(name="order_id", type="integer", nullable=false)
     */
    private $orderId;

    /**
     * @var integer
     *
     * @ORM\Column(name="product_id", type="integer", nullable=false)
     */
    private $productId;

    /**
     * @var integer
     *
     * @ORM\Column(name="unit_quantity", type="integer", nullable=false)
     */
    private $unitQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="unit_price", type="integer", nullable=false)
     */
    private $unitPrice;


    /**
     * @var \Frontend\OrderBundle\Entity\Product
     */
    private $product;

    /**
     * @var \Frontend\OrderBundle\Entity\Order
     */
    private $order;


    /**
     * Set orderId
     *
     * @param integer $orderId
     * @return OrderItem
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get orderId
     *
     * @return integer 
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set productId
     *
     * @param integer $productId
     * @return OrderItem
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
     * Set unitQuantity
     *
     * @param integer $unitQuantity
     * @return OrderItem
     */
    public function setUnitQuantity($unitQuantity)
    {
        $this->unitQuantity = $unitQuantity;

        return $this;
    }

    /**
     * Get unitQuantity
     *
     * @return integer 
     */
    public function getUnitQuantity()
    {
        return $this->unitQuantity;
    }

    /**
     * Set unitPrice
     *
     * @param integer $unitPrice
     * @return OrderItem
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get unitPrice
     *
     * @return integer 
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
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
     * @param \Frontend\OrderBundle\Entity\Product $product
     * @return OrderItem
     */
    public function setProduct(\Frontend\OrderBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Frontend\OrderBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set order
     *
     * @param \Frontend\OrderBundle\Entity\Order $order
     * @return OrderItem
     */
    public function setOrder(\Frontend\OrderBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \Frontend\OrderBundle\Entity\Order 
     */
    public function getOrder()
    {
        return $this->order;
    }
}
