<?php

namespace Frontend\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Profile
 *
 * @ORM\Table(name="profile")
 * @ORM\Entity
 */
class Profile
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=60, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="billing_address_city", type="string", length=30, nullable=true)
     */
    private $billingAddressCity;

    /**
     * @var string
     *
     * @ORM\Column(name="billing_address_street", type="string", length=30, nullable=true)
     */
    private $billingAddressStreet;

    /**
     * @var integer
     *
     * @ORM\Column(name="billing_address_street_number", type="integer", nullable=true)
     */
    private $billingAddressStreetNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="billing_address_zip_code", type="integer", nullable=true)
     */
    private $billingAddressZipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="shipping_address_city", type="string", length=30, nullable=true)
     */
    private $shippingAddressCity;

    /**
     * @var string
     *
     * @ORM\Column(name="shipping_address_street", type="string", length=30, nullable=true)
     */
    private $shippingAddressStreet;

    /**
     * @var integer
     *
     * @ORM\Column(name="shipping_address_street_number", type="integer", nullable=true)
     */
    private $shippingAddressStreetNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="shipping_address_zip_code", type="integer", nullable=true)
     */
    private $shippingAddressZipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=30, nullable=true)
     */
    private $telephone;


    /**
     * @var \Frontend\UserBundle\Entity\User
     */
    private $user;


    /**
     * Set userId
     *
     * @param integer $userId
     * @return Profile
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
     * Set name
     *
     * @param string $name
     * @return Profile
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
     * Set billingAddressCity
     *
     * @param string $billingAddressCity
     * @return Profile
     */
    public function setBillingAddressCity($billingAddressCity)
    {
        $this->billingAddressCity = $billingAddressCity;

        return $this;
    }

    /**
     * Get billingAddressCity
     *
     * @return string 
     */
    public function getBillingAddressCity()
    {
        return $this->billingAddressCity;
    }

    /**
     * Set billingAddressStreet
     *
     * @param string $billingAddressStreet
     * @return Profile
     */
    public function setBillingAddressStreet($billingAddressStreet)
    {
        $this->billingAddressStreet = $billingAddressStreet;

        return $this;
    }

    /**
     * Get billingAddressStreet
     *
     * @return string 
     */
    public function getBillingAddressStreet()
    {
        return $this->billingAddressStreet;
    }

    /**
     * Set billingAddressStreetNumber
     *
     * @param integer $billingAddressStreetNumber
     * @return Profile
     */
    public function setBillingAddressStreetNumber($billingAddressStreetNumber)
    {
        $this->billingAddressStreetNumber = $billingAddressStreetNumber;

        return $this;
    }

    /**
     * Get billingAddressStreetNumber
     *
     * @return integer 
     */
    public function getBillingAddressStreetNumber()
    {
        return $this->billingAddressStreetNumber;
    }

    /**
     * Set billingAddressZipCode
     *
     * @param integer $billingAddressZipCode
     * @return Profile
     */
    public function setBillingAddressZipCode($billingAddressZipCode)
    {
        $this->billingAddressZipCode = $billingAddressZipCode;

        return $this;
    }

    /**
     * Get billingAddressZipCode
     *
     * @return integer 
     */
    public function getBillingAddressZipCode()
    {
        return $this->billingAddressZipCode;
    }

    /**
     * Set shippingAddressCity
     *
     * @param string $shippingAddressCity
     * @return Profile
     */
    public function setShippingAddressCity($shippingAddressCity)
    {
        $this->shippingAddressCity = $shippingAddressCity;

        return $this;
    }

    /**
     * Get shippingAddressCity
     *
     * @return string 
     */
    public function getShippingAddressCity()
    {
        return $this->shippingAddressCity;
    }

    /**
     * Set shippingAddressStreet
     *
     * @param string $shippingAddressStreet
     * @return Profile
     */
    public function setShippingAddressStreet($shippingAddressStreet)
    {
        $this->shippingAddressStreet = $shippingAddressStreet;

        return $this;
    }

    /**
     * Get shippingAddressStreet
     *
     * @return string 
     */
    public function getShippingAddressStreet()
    {
        return $this->shippingAddressStreet;
    }

    /**
     * Set shippingAddressStreetNumber
     *
     * @param integer $shippingAddressStreetNumber
     * @return Profile
     */
    public function setShippingAddressStreetNumber($shippingAddressStreetNumber)
    {
        $this->shippingAddressStreetNumber = $shippingAddressStreetNumber;

        return $this;
    }

    /**
     * Get shippingAddressStreetNumber
     *
     * @return integer 
     */
    public function getShippingAddressStreetNumber()
    {
        return $this->shippingAddressStreetNumber;
    }

    /**
     * Set shippingAddressZipCode
     *
     * @param integer $shippingAddressZipCode
     * @return Profile
     */
    public function setShippingAddressZipCode($shippingAddressZipCode)
    {
        $this->shippingAddressZipCode = $shippingAddressZipCode;

        return $this;
    }

    /**
     * Get shippingAddressZipCode
     *
     * @return integer 
     */
    public function getShippingAddressZipCode()
    {
        return $this->shippingAddressZipCode;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return Profile
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
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
     * Set user
     *
     * @param \Frontend\UserBundle\Entity\User $user
     * @return Profile
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
