<?php

namespace Modules\AuthBundle\Entity;

use BaseClasses\BaseModel;
use FOS\UserBundle\Model\User as FOSUser;

/**
 * User
 */
class User extends FOSUser
{
    /**
     * @var int
     */
    protected $id;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @var string
     */
    private $phone;

    /**
     * @var \Modules\AuthBundle\Entity\UserProfile
     */
    private $profile;


    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set profile
     *
     * @param \Modules\AuthBundle\Entity\UserProfile $profile
     *
     * @return User
     */
    public function setProfile(\Modules\AuthBundle\Entity\UserProfile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \Modules\AuthBundle\Entity\UserProfile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @var boolean
     */
    private $activation;


    /**
     * Set activation
     *
     * @param boolean $activation
     *
     * @return User
     */
    public function setActivation($activation)
    {
        $this->activation = $activation;

        return $this;
    }

    /**
     * Get activation
     *
     * @return boolean
     */
    public function getActivation()
    {
        return $this->activation;
    }

    /**
     * @var boolean
     */
    private $locked;


    /**
     * Set locked
     *
     * @param boolean $locked
     *
     * @return User
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return boolean
     */
    public function getLocked()
    {
        return $this->locked;
    }

    public function isLocked()
    {
        return !$this->isAccountNonLocked();
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return !$this->locked;
    }
}
