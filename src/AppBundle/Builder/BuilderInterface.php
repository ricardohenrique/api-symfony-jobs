<?php

namespace AppBundle\Builder;

use AppBundle\Entity\EntityInterface;

interface BuilderInterface
{
    public static function build(array $params): EntityInterface;
}
