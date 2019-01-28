<?php

namespace AppBundle\Services;

use AppBundle\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;

class Service extends AbstractService
{
    /**
     * Service constructor.
     * @param ServiceRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ServiceRepository $repository,
        EntityManagerInterface $entityManager
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }
}
