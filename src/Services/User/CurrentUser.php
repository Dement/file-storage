<?php

namespace Services\User;

use BaseExceptions\ApiException;
use Modules\AuthBundle\{
    Entity\User,
    Entity\UserProfile,
    Repository\UserProfileRepository
};

class CurrentUser {

    /** @var User */
    private $user = null;

    /** @var UserProfile */
    private $profile = null;

    /**
     * Set the current user
     *
     * @param User $user
     */
    public function setUser(User $user) {
        if(is_null($this->user)) {
            $this->user = $user;
        }
    }

    /**
     * Get the current user
     *
     * @return User
     */
    public function getUser() : User
    {
        return $this->user;
    }

    /**
     * @return UserProfile
     */
    public function getProfile() : UserProfile
    {
        if(is_null($this->profile)) {
            $this->profile = UserProfileRepository::get()->getByUserId($this->user->getId());
        }

        return $this->profile;
    }

    /**
     * Method Checks whether the current user
     *
     * @param UserProfile $profile
     * @return bool
     */
    public function checkCurrentUser(UserProfile $profile) : bool
    {
        if($this->getProfile()->getId() == $profile->getId()) {
            return true;
        }

        return false;
    }

    /**
     * Method Checks whether the current user if there is an error
     *
     * @param UserProfile $profile
     * @throws ApiException
     */
    public function checkCurrentUserWithError(UserProfile $profile)
    {
        if(!$this->checkCurrentUser($profile)) {
            throw new ApiException('access_denied', 403);
        }
    }



}