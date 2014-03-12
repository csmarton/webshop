<?php

namespace Frontend\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

     /**
     * @ORM\OneToOne(targetEntity="\Frontend\ProfileBundle\Entity\Profile", mappedBy="user")
     */
    protected $profile;
    /**
     * Constructor
     */
    public function __construct()
    {
        
    }

  
    protected $user;


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
     * Set profile
     *
     * @param \Frontend\ProfileBundle\Entity\Profile $profile
     * @return User
     */
    public function setProfile(\Frontend\ProfileBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \Frontend\ProfileBundle\Entity\Profile 
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
