<?php

namespace Serializer\Serializers;

class JsonSerializer implements SerializerInterface
{

    public function serialize($data)
    {
        return json_encode($data);
    }
}