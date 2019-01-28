<?php

namespace AppBundle\Services;

use AppBundle\Repository\ZipcodeRepository;
use Doctrine\ORM\EntityManagerInterface;

class Zipcode extends AbstractService
{
    /**
     * Service constructor.
     * @param ZipcodeRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ZipcodeRepository $repository,
        EntityManagerInterface $entityManager
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }
}
