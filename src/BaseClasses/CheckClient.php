<?php

namespace BaseClasses;

use BaseExceptions\ApiException;
use FOS\UserBundle\Model\User as FOSUser;

class CheckClient
{
    /**
     * The method checks whether the object exists or not and throws exception
     *
     * @param $model
     * @throws ApiException
     */
    public static function modelExistenceOrNull($model)
    {
        if (is_null($model)) {
            throw new ApiException('access_denied_or_no_content', 404);
        }

        if ((!$model instanceof BaseModel) && (!$model instanceof FOSUser)) {
            throw new ApiException('wrong_type_of_object_expected', 406);
        }
    }

}