<?php

namespace AppBundle\Builder;

use AppBundle\Entity\EntityInterface;
use AppBundle\Entity\Job as JobEntity;
use DateTime;

class Job implements BuilderInterface
{
    public static function build(array $parameters): EntityInterface
    {
        $attributes = [];
        $attributes['serviceId'] = $parameters['serviceId'] ?? null;
        $attributes['zipcodeId'] = $parameters['zipcodeId'] ?? null;
        $attributes['title'] = $parameters['title'] ?? null;
        $attributes['description'] = $parameters['description'] ?? null;
        $attributes['dateToBeDone'] = isset($parameters['dateToBeDone'])
            ? new DateTime($parameters['dateToBeDone'])
            : null;
        $attributes['id'] = $parameters['id'] ?? null;

        return new JobEntity(
            $attributes['serviceId'],
            $attributes['zipcodeId'],
            $attributes['title'],
            $attributes['description'],
            $attributes['dateToBeDone'],
            $attributes['id']
        );
    }
}
