<?php

namespace Frontend\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrdersProfileInformation
 *
 * @ORM\Table(name="orders_profile_information")
 * @ORM\Entity
 */
class OrdersProfileInformation
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
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=30, nullable=false)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="billing_address_city", type="string", length=60, nullable=false)
     */
    private $billingAddressCity;

    /**
     * @var integer
     *
     * @ORM\Column(name="billing_address_street", type="integer", nullable=false)
     */
    private $billingAddressStreet;

    /**
     * @var integer
     *
     * @ORM\Column(name="billing_address_street_number", type="integer", nullable=false)
     */
    private $billingAddressStreetNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="billing_address_zip_code", type="integer", nullable=false)
     */
    private $billingAddressZipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="shipping_address_city", type="string", length=60, nullable=true)
     */
    private $shippingAddressCity;

    /**
     * @var integer
     *
     * @ORM\Column(name="shipping_address_street", type="integer", nullable=true)
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
     * Set name
     *
     * @param string $name
     * @return OrdersProfileInformation
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
     * Set email
     *
     * @param string $email
     * @return OrdersProfileInformation
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return OrdersProfileInformation
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
     * Set billingAddressCity
     *
     * @param string $billingAddressCity
     * @return OrdersProfileInformation
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
     * @param integer $billingAddressStreet
     * @return OrdersProfileInformation
     */
    public function setBillingAddressStreet($billingAddressStreet)
    {
        $this->billingAddressStreet = $billingAddressStreet;

        return $this;
    }

    /**
     * Get billingAddressStreet
     *
     * @return integer 
     */
    public function getBillingAddressStreet()
    {
        return $this->billingAddressStreet;
    }

    /**
     * Set billingAddressStreetNumber
     *
     * @param integer $billingAddressStreetNumber
     * @return OrdersProfileInformation
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
     * @return OrdersProfileInformation
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
     * @return OrdersProfileInformation
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
     * @param integer $shippingAddressStreet
     * @return OrdersProfileInformation
     */
    public function setShippingAddressStreet($shippingAddressStreet)
    {
        $this->shippingAddressStreet = $shippingAddressStreet;

        return $this;
    }

    /**
     * Get shippingAddressStreet
     *
     * @return integer 
     */
    public function getShippingAddressStreet()
    {
        return $this->shippingAddressStreet;
    }

    /**
     * Set shippingAddressStreetNumber
     *
     * @param integer $shippingAddressStreetNumber
     * @return OrdersProfileInformation
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
     * @return OrdersProfileInformation
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
