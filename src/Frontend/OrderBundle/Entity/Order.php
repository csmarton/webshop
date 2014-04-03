<?php

namespace Frontend\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Order
 *
 * @ORM\Table(name="order")
 * @ORM\Entity
 */
class Order
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
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="items_total", type="integer", nullable=false)
     */
    private $itemsTotal;

    /**
     * @var integer
     *
     * @ORM\Column(name="items_total_price", type="integer", nullable=false)
     */
    private $itemsTotalPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=false)
     */
    private $comment;

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
     * @var integer
     *
     * @ORM\Column(name="payment_option_id", type="integer", nullable=false)
     */
    private $paymentOptionId;

    /**
     * @var integer
     *
     * @ORM\Column(name="shipping_option_id", type="integer", nullable=false)
     */
    private $shippingOptionId;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_state", type="string", length=60, nullable=false)
     */
    private $paymentState;

    /**
     * @var string
     *
     * @ORM\Column(name="shipping_state", type="string", length=60, nullable=false)
     */
    private $shippingState;

    /**
     * @var boolean
     *
     * @ORM\Column(name="accept_conditions", type="boolean", nullable=false)
     */
    private $acceptConditions;


    /**
     * @var \Frontend\OrderBundle\Entity\ShippingOption
     */
    private $shippingOption;

    /**
     * @var \Frontend\OrderBundle\Entity\PaymentOption
     */
    private $paymentOption;


    /**
     * Set userId
     *
     * @param integer $userId
     * @return Order
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set itemsTotal
     *
     * @param integer $itemsTotal
     * @return Order
     */
    public function setItemsTotal($itemsTotal)
    {
        $this->itemsTotal = $itemsTotal;

        return $this;
    }

    /**
     * Get itemsTotal
     *
     * @return integer 
     */
    public function getItemsTotal()
    {
        return $this->itemsTotal;
    }

    /**
     * Set itemsTotalPrice
     *
     * @param integer $itemsTotalPrice
     * @return Order
     */
    public function setItemsTotalPrice($itemsTotalPrice)
    {
        $this->itemsTotalPrice = $itemsTotalPrice;

        return $this;
    }

    /**
     * Get itemsTotalPrice
     *
     * @return integer 
     */
    public function getItemsTotalPrice()
    {
        return $this->itemsTotalPrice;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Order
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Order
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
     * @return Order
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
     * Set paymentOptionId
     *
     * @param integer $paymentOptionId
     * @return Order
     */
    public function setPaymentOptionId($paymentOptionId)
    {
        $this->paymentOptionId = $paymentOptionId;

        return $this;
    }

    /**
     * Get paymentOptionId
     *
     * @return integer 
     */
    public function getPaymentOptionId()
    {
        return $this->paymentOptionId;
    }

    /**
     * Set shippingOptionId
     *
     * @param integer $shippingOptionId
     * @return Order
     */
    public function setShippingOptionId($shippingOptionId)
    {
        $this->shippingOptionId = $shippingOptionId;

        return $this;
    }

    /**
     * Get shippingOptionId
     *
     * @return integer 
     */
    public function getShippingOptionId()
    {
        return $this->shippingOptionId;
    }

    /**
     * Set paymentState
     *
     * @param string $paymentState
     * @return Order
     */
    public function setPaymentState($paymentState)
    {
        $this->paymentState = $paymentState;

        return $this;
    }

    /**
     * Get paymentState
     *
     * @return string 
     */
    public function getPaymentState()
    {
        return $this->paymentState;
    }

    /**
     * Set shippingState
     *
     * @param string $shippingState
     * @return Order
     */
    public function setShippingState($shippingState)
    {
        $this->shippingState = $shippingState;

        return $this;
    }

    /**
     * Get shippingState
     *
     * @return string 
     */
    public function getShippingState()
    {
        return $this->shippingState;
    }

    /**
     * Set acceptConditions
     *
     * @param boolean $acceptConditions
     * @return Order
     */
    public function setAcceptConditions($acceptConditions)
    {
        $this->acceptConditions = $acceptConditions;

        return $this;
    }

    /**
     * Get acceptConditions
     *
     * @return boolean 
     */
    public function getAcceptConditions()
    {
        return $this->acceptConditions;
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
     * Set shippingOption
     *
     * @param \Frontend\OrderBundle\Entity\ShippingOption $shippingOption
     * @return Order
     */
    public function setShippingOption(\Frontend\OrderBundle\Entity\ShippingOption $shippingOption = null)
    {
        $this->shippingOption = $shippingOption;

        return $this;
    }

    /**
     * Get shippingOption
     *
     * @return \Frontend\OrderBundle\Entity\ShippingOption 
     */
    public function getShippingOption()
    {
        return $this->shippingOption;
    }

    /**
     * Set paymentOption
     *
     * @param \Frontend\OrderBundle\Entity\PaymentOption $paymentOption
     * @return Order
     */
    public function setPaymentOption(\Frontend\OrderBundle\Entity\PaymentOption $paymentOption = null)
    {
        $this->paymentOption = $paymentOption;

        return $this;
    }

    /**
     * Get paymentOption
     *
     * @return \Frontend\OrderBundle\Entity\PaymentOption 
     */
    public function getPaymentOption()
    {
        return $this->paymentOption;
    }
}
