<?php

namespace AppBundle\Builder;

use AppBundle\Entity\EntityInterface;
use AppBundle\Entity\Zipcode as ZipcodeEntity;

class Zipcode implements BuilderInterface
{

    public static function build(array $parameters): EntityInterface
    {
        $attributes = [];
        $attributes['id'] = $parameters['id'] ?? null;
        $attributes['city'] = $parameters['city'] ?? null;

        return new ZipcodeEntity($attributes['id'], $attributes['city']);
    }
}