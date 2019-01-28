<?php

namespace AppBundle\Services;

use AppBundle\Entity\EntityInterface;
use AppBundle\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Entity\Job as JobEntity;

class Job extends AbstractService
{
    /**
     * @var Service
     */
    private $service;

    /**
     * @var Zipcode
     */
    private $zipcode;

    /**
     * Job constructor.
     * @param JobRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        JobRepository $repository,
        Service $service,
        Zipcode $zipcode,
        EntityManagerInterface $entityManager
    ) {
        $this->repository = $repository;
        $this->service = $service;
        $this->zipcode = $zipcode;
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $params
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findAll(array $params = []): array
    {
        $sql = "SELECT * FROM job WHERE created_at BETWEEN NOW() - INTERVAL 30 DAY AND NOW()";
        if (!empty($params['service'])) {
            $sql .= ' AND service_id = ' . $params['service'];
        }

        if (!empty($params['zipcode'])) {
            $sql .= ' AND zipcode_id = ' . $params['zipcode'];
        }

        $stmt = $this->entityManager
            ->getConnection()
            ->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    public function create(EntityInterface $entity): EntityInterface
    {
        $this->basicValidation($entity);
        $this->validateForeignKeys($entity);

        return $this->save($entity);
    }

    /**
     * @param EntityInterface $entity
     * @throws NotFoundHttpException
     * @return EntityInterface
     */
    public function update(EntityInterface $entity): JobEntity
    {
        $this->basicValidation($entity);
        $this->validateForeignKeys($entity);

        /** @var JobEntity $persistedEntity */
        $persistedEntity = $this->find($entity->getId());
        if (is_null($persistedEntity)) {
            throw new NotFoundHttpException(sprintf(
                'The resource \'%s\' was not found.',
                $entity->getId()
            ));
        }

        return $this->save($entity);
    }

    /**
     * @param JobEntity $entity
     */
    private function validateForeignKeys(JobEntity $entity): void
    {
        if (!$this->service->find($entity->getServiceId())) {
            throw new BadRequestHttpException(sprintf(
                'Service \'%s\' was not found',
                $entity->getServiceId()
            ));
        }

        if (!$this->zipcode->find($entity->getZipcodeId())) {
            throw new BadRequestHttpException(sprintf(
                'Zipcode \'%s\' was not found',
                $entity->getZipcodeId()
            ));
        }
    }

    /**
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    protected function save(EntityInterface $entity): EntityInterface
    {
        if (is_null($entity->getId())) {
            $this->entityManager->persist($entity);
        } else {
            $this->entityManager->merge($entity);
        }

        $this->entityManager->flush();

        return $entity;
    }
}
