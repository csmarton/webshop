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
    
    private $isAdmin;
    
    public function getIsAdmin(){
            return in_array("ROLE_ADMIN",$this->roles);
    }

    public function setIsAdmin(){            
        $this->isAdmin = in_array("ROLE_ADMIN",$this->roles);
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
         parent::__construct();
         $this->isAdmin = in_array("ROLE_ADMIN",$this->roles);
    }

    protected $user;
    
    public function getUser(){
            return $this->user;
    }

    public function setUser($user){
            $this->user = $user;
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
    
    /**
    * Set email
    *
    * @param string $email
    * @return User
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
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->email;
    }

}
