<?php

namespace Frontend\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrdersItem
 *
 * @ORM\Table(name="orders_item")
 * @ORM\Entity
 */
class OrdersItem
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
     * @var string
     *
     * @ORM\Column(name="product_name", type="string", length=100, nullable=false)
     */
    private $productName;

    /**
     * @var integer
     *
     * @ORM\Column(name="unit_quantity", type="integer", nullable=false)
     */
    private $unitQuantity;

    /**
     * @var float
     *
     * @ORM\Column(name="unit_price", type="float", precision=10, scale=0, nullable=false)
     */
    private $unitPrice;


    /**
     * @var \Frontend\ProductBundle\Entity\Product
     */
    private $product;

    /**
     * @var \Frontend\OrderBundle\Entity\Orders
     */
    private $orders;


    /**
     * Set orderId
     *
     * @param integer $orderId
     * @return OrdersItem
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
     * @return OrdersItem
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
     * Set productName
     *
     * @param string $productName
     * @return OrdersItem
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;

        return $this;
    }

    /**
     * Get productName
     *
     * @return string 
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * Set unitQuantity
     *
     * @param integer $unitQuantity
     * @return OrdersItem
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
     * @param float $unitPrice
     * @return OrdersItem
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get unitPrice
     *
     * @return float 
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
     * @param \Frontend\ProductBundle\Entity\Product $product
     * @return OrdersItem
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
     * Set orders
     *
     * @param \Frontend\OrderBundle\Entity\Orders $orders
     * @return OrdersItem
     */
    public function setOrders(\Frontend\OrderBundle\Entity\Orders $orders = null)
    {
        $this->orders = $orders;

        return $this;
    }

    /**
     * Get orders
     *
     * @return \Frontend\OrderBundle\Entity\Orders 
     */
    public function getOrders()
    {
        return $this->orders;
    }
}
