<?php

namespace Frontend\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity
 */
class Orders
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
     * @ORM\Column(name="items_total", type="integer", nullable=true)
     */
    private $itemsTotal;

    /**
     * @var integer
     *
     * @ORM\Column(name="items_total_price", type="integer", nullable=true)
     */
    private $itemsTotalPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ordered_at", type="datetime", nullable=false)
     */
    private $orderedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="performed_at", type="datetime", nullable=true)
     */
    private $performedAt;

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
     * @ORM\Column(name="payment_state", type="string", length=60, nullable=true)
     */
    private $paymentState;

    /**
     * @var string
     *
     * @ORM\Column(name="shipping_state", type="string", length=60, nullable=true)
     */
    private $shippingState;

    /**
     * @var boolean
     *
     * @ORM\Column(name="accept_conditions", type="boolean", nullable=false)
     */
    private $acceptConditions;


    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $orderItems;

    /**
     * @var \Frontend\OrderBundle\Entity\ShippingOption
     */
    private $shippingOption;

    /**
     * @var \Frontend\OrderBundle\Entity\PaymentOption
     */
    private $paymentOption;

    /**
     * @var \Frontend\UserBundle\Entity\User
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orderItems = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Orders
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
     * @return Orders
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
     * @return Orders
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
     * @return Orders
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
     * Set orderedAt
     *
     * @param \DateTime $orderedAt
     * @return Orders
     */
    public function setOrderedAt($orderedAt)
    {
        $this->orderedAt = $orderedAt;

        return $this;
    }

    /**
     * Get orderedAt
     *
     * @return \DateTime 
     */
    public function getOrderedAt()
    {
        return $this->orderedAt;
    }

    /**
     * Set performedAt
     *
     * @param \DateTime $performedAt
     * @return Orders
     */
    public function setPerformedAt($performedAt)
    {
        $this->performedAt = $performedAt;

        return $this;
    }

    /**
     * Get performedAt
     *
     * @return \DateTime 
     */
    public function getPerformedAt()
    {
        return $this->performedAt;
    }

    /**
     * Set paymentOptionId
     *
     * @param integer $paymentOptionId
     * @return Orders
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
     * @return Orders
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
     * @return Orders
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
     * @return Orders
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
     * @return Orders
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
     * Add orderItems
     *
     * @param \Frontend\OrderBundle\Entity\OrdersItem $orderItems
     * @return Orders
     */
    public function addOrderItem(\Frontend\OrderBundle\Entity\OrdersItem $orderItems)
    {
        $this->orderItems[] = $orderItems;

        return $this;
    }

    /**
     * Remove orderItems
     *
     * @param \Frontend\OrderBundle\Entity\OrdersItem $orderItems
     */
    public function removeOrderItem(\Frontend\OrderBundle\Entity\OrdersItem $orderItems)
    {
        $this->orderItems->removeElement($orderItems);
    }

    /**
     * Get orderItems
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }

    /**
     * Set shippingOption
     *
     * @param \Frontend\OrderBundle\Entity\ShippingOption $shippingOption
     * @return Orders
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
     * @return Orders
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

    /**
     * Set user
     *
     * @param \Frontend\UserBundle\Entity\User $user
     * @return Orders
     */
    public function setUser(\Frontend\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Frontend\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
