<?php

namespace Tests\AppBundle\Services;

use AppBundle\Entity\Job as JobEntity;
use AppBundle\Entity\Service as ServiceEntity;
use AppBundle\Entity\Zipcode as ZipcodeEntity;
use AppBundle\Repository\JobRepository;
use AppBundle\Services\Job;
use AppBundle\Services\Service;
use AppBundle\Services\Zipcode;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Tests\Functional\WebTestCase;
use DateTime;

/**
 * @group unit
 */
class JobTest extends AbstractServicesTest
{
    /**
     * @var JobRepository
     */
    private $repository;

    /**
     * @var Service
     */
    private $service;

    /**
     * @var Zipcode
     */
    private $zipcode;

    /**
     * @var JobEntity
     */
    private $defaultEntity;

    /**
     * @var JobEntity
     */
    private $persistedEntity;

    public function setUp()
    {
        parent::setUp();
        $this->repository = $this->getMockBuilder(JobRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findAll', 'find'])
            ->getMock();

        $this->service = $this->getMockBuilder(Service::class)
            ->disableOriginalConstructor()
            ->setMethods(['find'])
            ->getMock();

        $this->zipcode = $this->getMockBuilder(Zipcode::class)
            ->disableOriginalConstructor()
            ->setMethods(['find'])
            ->getMock();

        $this->defaultEntity = new JobEntity(
            802031,
            '01621',
            'Job to be done',
            'description',
            new DateTime('2018-11-11')
        );
        $this->persistedEntity = new JobEntity(
            802031,
            '01621',
            'Job to be done',
            'description',
            new DateTime('2018-11-11'),
            'a1c59e8f-ca88-11e8-94bd-0242ac130005'
        );
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage zipcode_id: This value should have exactly 5 characters., title: The title must more than 4 characters
     */
    public function testCreateJobWithInvalidDataThrowsBadRequestHttpException()
    {
        $this->service
            ->expects($this->never())
            ->method('find');
        $this->zipcode
            ->expects($this->never())
            ->method('find');
        $this->entityManager
            ->expects($this->never())
            ->method('persist');
        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $job = new Job(
            $this->repository,
            $this->service,
            $this->zipcode,
            $this->entityManager
        );
        $job->create(new JobEntity(
            802031,
            '123',
            'Job',
            'description',
            new DateTime('2018-11-11')
        ));
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage Service '802031' was not found
     */
    public function testCreateJobWithServiceNotFoundThrowsBadRequestHttpException()
    {
        $this->service
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue(null))
            ->with(802031);
        $this->zipcode
            ->expects($this->never())
            ->method('find');
        $this->entityManager
            ->expects($this->never())
            ->method('persist');
        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $job = new Job(
            $this->repository,
            $this->service,
            $this->zipcode,
            $this->entityManager
        );
        $job->create(new JobEntity(
            802031,
            '12345',
            'Job to be done',
            'description',
            new DateTime('2018-11-11')
        ));
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage Zipcode '12345' was not found
     */
    public function testCreateJobWithZipcodeNotFoundThrowsBadRequestHttpException()
    {
        $this->service
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue(new ServiceEntity()))
            ->with(802031);
        $this->zipcode
            ->method('find')
            ->will($this->returnValue(null))
            ->with('12345');
        $this->entityManager
            ->expects($this->never())
            ->method('persist');
        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $job = new Job(
            $this->repository,
            $this->service,
            $this->zipcode,
            $this->entityManager
        );
        $job->create(new JobEntity(
            802031,
            '12345',
            'Job to be done',
            'description',
            new DateTime('2018-11-11')
        ));
    }

    public function testCreateJobWithValidJobReturnsPersistedJob()
    {
        $this->service
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue(new ServiceEntity()))
            ->with(802031);
        $this->zipcode
            ->method('find')
            ->will($this->returnValue(new ZipcodeEntity()))
            ->with('01621');
        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->defaultEntity);
        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $job = new Job(
            $this->repository,
            $this->service,
            $this->zipcode,
            $this->entityManager
        );
        $job->create($this->defaultEntity);
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage The resource 'a1c59e8f-ca88-11e8-94bd-0242ac130005
     */
    public function testUpdateJobWithNotFoundThrowsBadRequestHttpException()
    {
        $this->repository
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue(null))
            ->with('a1c59e8f-ca88-11e8-94bd-0242ac130005');
        $this->service
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue(new ServiceEntity()))
            ->with(802031);
        $this->zipcode
            ->method('find')
            ->will($this->returnValue(new ZipcodeEntity()))
            ->with('01621');
        $this->entityManager
            ->expects($this->never())
            ->method('merge');
        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $job = new Job(
            $this->repository,
            $this->service,
            $this->zipcode,
            $this->entityManager
        );
        $job->update($this->persistedEntity);
    }

    public function testUpdateJobValidReturnsPersistedJob()
    {
        $this->repository
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue($this->persistedEntity))
            ->with('a1c59e8f-ca88-11e8-94bd-0242ac130005');
        $this->service
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue(new ServiceEntity()))
            ->with(802031);
        $this->zipcode
            ->method('find')
            ->will($this->returnValue(new ZipcodeEntity()))
            ->with('01621');
        $this->entityManager
            ->expects($this->once())
            ->method('merge')
            ->with($this->persistedEntity);
        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $job = new Job(
            $this->repository,
            $this->service,
            $this->zipcode,
            $this->entityManager
        );
        $job->update($this->persistedEntity);
    }
}
