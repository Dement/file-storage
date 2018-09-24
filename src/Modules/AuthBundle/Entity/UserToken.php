<?php

namespace Modules\AuthBundle\Entity;

use BaseClasses\BaseModel;

/**
 * UserToken
 */
class UserToken extends BaseModel
{
    /**
     * @var int
     */
    private $id;


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
    private $token;

    /**
     * @var integer
     */
    private $expiredTime;

    /**
     * Set token
     *
     * @param string $token
     *
     * @return UserToken
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set expiredTime
     *
     * @param integer $expiredTime
     *
     * @return UserToken
     */
    public function setExpiredTime($expiredTime)
    {
        $this->expiredTime = $expiredTime;

        return $this;
    }

    /**
     * Get expiredTime
     *
     * @return integer
     */
    public function getExpiredTime()
    {
        return $this->expiredTime;
    }

    /**
     * @var \Modules\AuthBundle\Entity\User
     */
    private $user;


    /**
     * Set user
     *
     * @param \Modules\AuthBundle\Entity\User $user
     *
     * @return UserToken
     */
    public function setUser(\Modules\AuthBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Modules\AuthBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
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
     * @return UserToken
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
     * @var integer
     */
    private $authCode;


    /**
     * Set authCode
     *
     * @param integer $authCode
     *
     * @return UserToken
     */
    public function setAuthCode($authCode)
    {
        $this->authCode = $authCode;

        return $this;
    }

    /**
     * Get authCode
     *
     * @return integer
     */
    public function getAuthCode()
    {
        return $this->authCode;
    }
}
