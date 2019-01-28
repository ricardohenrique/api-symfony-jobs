<?php

namespace AppBundle\Services;

use AppBundle\Entity\EntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\RecursiveValidator;

abstract class AbstractService
{
    /**
     * @var ServiceEntityRepositoryInterface
     */
    protected $repository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param $id
     * @return null|EntityInterface
     */
    public function find($id): ?EntityInterface
    {
        try {
            $entity = $this->repository->find($id);
        } catch (\Exception $exception) {
            $entity = null;
        }

        return $entity;
    }

    /**
     * @param EntityInterface $entity
     * @throws BadRequestHttpException
     * @return EntityInterface
     */
    public function create(EntityInterface $entity): EntityInterface
    {
        $this->basicValidation($entity);

        if ($this->find($entity->getId())) {
            throw new BadRequestHttpException(sprintf(
                'Resource \'%s\' already exists',
                $entity->getId()
            ));
        }

        return $this->save($entity);
    }

    /**
     * @param EntityInterface $entity
     * @throws BadRequestHttpException
     * @todo extractToAnotherClass
     */
    protected function basicValidation(EntityInterface $entity): void
    {
        /** @var RecursiveValidator $validator */
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($entity);
        if (count($errors)) {
            /** @todo Create an errorMessageHandler */
            $errorMessages = $this->getErrorMessages($errors);

            throw new BadRequestHttpException(implode($errorMessages, ', '));
        }
    }

    /**
     * @param $errors
     * @return array
     */
    private function getErrorMessages($errors): array
    {
        $errorMessages = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $errorMessages[] = sprintf(
                '%s: %s',
                $error->getPropertyPath(),
                $error->getMessage()
            );
        }

        return $errorMessages;
    }

    /**
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    protected function save(EntityInterface $entity): EntityInterface
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }
}
