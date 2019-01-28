<?php

namespace Tests\AppBundle\Services;

use AppBundle\Entity\Service as ServiceEntity;
use AppBundle\Repository\ServiceRepository;
use AppBundle\Services\Service;

/**
 * @group unit
 */
class ServiceTest extends AbstractServicesTest
{
    /**
     * @var ServiceRepository
     */
    private $serviceRepository;

    /**
     * @var ServiceEntity
     */
    protected $defaultServiceEntity;

    public function setUp()
    {
        parent::setUp();
        $this->serviceRepository = $this->getMockBuilder(ServiceRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findAll', 'find'])
            ->getMock();

        $this->defaultServiceEntity = new ServiceEntity(1, 'service');
    }

    public function testFindAllWithoutValueReturnsEmptyArray()
    {
        $this->serviceRepository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue([]));

        $service = new Service($this->serviceRepository, $this->entityManager);
        $this->assertEmpty($service->findAll());
    }

    public function testFindAllWithServicesFoundReturnsArrayWithServices()
    {
        $this->serviceRepository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue([$this->defaultServiceEntity]));

        $service = new Service($this->serviceRepository, $this->entityManager);
        $this->assertEquals([$this->defaultServiceEntity], $service->findAll());
    }

    public function testFindWhenServiceIsNotFoundReturnsNull()
    {
        $service = new Service($this->serviceRepository, $this->entityManager);
        $this->assertNull($service->find(1));
    }

    public function testFindWhenServiceIsFoundReturnsService()
    {
        $this->serviceRepository
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue($this->defaultServiceEntity))
            ->with(1);

        $service = new Service($this->serviceRepository, $this->entityManager);
        $this->assertEquals($this->defaultServiceEntity, $service->find(1));
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage name: This value should not be blank.
     */
    public function testCreateWithInvalidServiceThrowsBadRequestHttpException()
    {
        $this->serviceRepository
            ->expects($this->never())
            ->method('find');
        $this->entityManager
            ->expects($this->never())
            ->method('persist');
        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $service = new Service($this->serviceRepository, $this->entityManager);
        $service->create(new ServiceEntity(1, ''));
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage Resource '1' already exists
     */
    public function testCreateWithDuplicatedServiceThrowsBadRequestHttpException()
    {
        $this->serviceRepository
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue($this->defaultServiceEntity))
            ->with(1);
        $this->entityManager
            ->expects($this->never())
            ->method('persist');
        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $service = new Service($this->serviceRepository, $this->entityManager);
        $service->create($this->defaultServiceEntity);
    }

    public function testCreateWithValidServiceReturnsPersistedService()
    {
        $this->serviceRepository
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue(null))
            ->with(1);
        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->defaultServiceEntity);
        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $service = new Service($this->serviceRepository, $this->entityManager);
        $service->create($this->defaultServiceEntity);
    }
}
