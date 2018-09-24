<?php

namespace Modules\AuthBundle\Entity;

use BaseClasses\BaseModel;
use Modules\CallSetupBundle\Repository\{
    CallCategoryRepository,
    CallTimeRepository
};
use Modules\SearchBundle\Repository\SearchRequestRepository;

/**
 * UserProfile
 */
class UserProfile extends BaseModel
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
    private $lastName;

    /**
     * @var string
     */
    private $name;


    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return UserProfile
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return UserProfile
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
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var \DateTime
     */
    private $deleted;

    /**
     * @var \Modules\AuthBundle\Entity\User
     */
    private $user;


    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return UserProfile
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return UserProfile
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set deleted
     *
     * @param \DateTime $deleted
     *
     * @return UserProfile
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return \DateTime
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set user
     *
     * @param \Modules\AuthBundle\Entity\User $user
     *
     * @return UserProfile
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

    public function __toString()
    {
        if(is_null($this->getLastName()) && is_null($this->getName())) {
            return 'Неизвестный пользователь';
        }

        return $this->getLastName() . ' ' . $this->getName();
    }
}
