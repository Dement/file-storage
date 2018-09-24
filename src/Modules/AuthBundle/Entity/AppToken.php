<?php

namespace Modules\AuthBundle\Entity;

use BaseClasses\BaseModel;

/**
 * AppToken
 */
class AppToken extends BaseModel
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
     * @return AppToken
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
     * @return AppToken
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
}
