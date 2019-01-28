<?php

namespace AppBundle\Builder;

use AppBundle\Entity\EntityInterface;
use AppBundle\Entity\Service as EntityService;

class Service implements BuilderInterface
{
    public static function build(array $parameters): EntityInterface
    {
        $attributes = [];
        $attributes['id'] = $parameters['id'] ?? null;
        $attributes['name'] = $parameters['name'] ?? null;

        return new EntityService($attributes['id'], $attributes['name']);
    }
}
