<?php

namespace Helpers;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestHelper
 * @package Helpers
 */
class RequestHelper
{
    /**
     * Converts the data from the request in the array
     *
     * @param Request $request
     * @return array
     */
    public static function formatForm(Request $request) : array
    {
        $result = [];

        $data = json_decode($request->getContent());

        if (!isset($data)) {
            return $result;
        }

        foreach($data as $key => $item) {
            if(is_object($item) && isset($item->id)) {
                $result[$key] = $item->id;
            } else {
                $result[$key] = $item;
            }
        }

        return $result;
    }

}