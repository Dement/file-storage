<?php

namespace Traits;

use Modules\AuthBundle\Entity\User;
use Modules\AuthBundle\Entity\UserProfile;

trait CurrentUser
{
    use ContainerAwareTrait;

    /**
     * Get the current user
     *
     * @return User
     */
    public function getCurrentUser() : User
    {
        return self::getContainer()->get('current_user')->getUser();
    }

    /**
     * Get the current profile
     *
     * @return UserProfile
     */
    public function getCurrentProfile() : UserProfile
    {
        return self::getContainer()->get('current_user')->getProfile();
    }

    /**
     * Checks whether the current user belongs to the essence
     *
     * @param UserProfile $profile - inspected object
     */
    public function checkCurrentProfile(UserProfile $profile)
    {
        return self::getContainer()->get('current_user')->checkCurrentUserWithError($profile);
    }
}