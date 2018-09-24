<?php

namespace Modules\BucketBundle\Entity;
use BaseClasses\BaseModel;

/**
 * Bucket
 */
class Bucket extends BaseModel
{
    /**
     * @var int
     */
    private $id;


    /**
     * Get id.
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
    private $title;

    /**
     * @var \Modules\AuthBundle\Entity\UserProfile
     */
    private $profile;


    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Bucket
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set profile.
     *
     * @param \Modules\AuthBundle\Entity\UserProfile|null $profile
     *
     * @return Bucket
     */
    public function setProfile(\Modules\AuthBundle\Entity\UserProfile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile.
     *
     * @return \Modules\AuthBundle\Entity\UserProfile|null
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
