<?php

namespace Tests\AppBundle\Services;

use AppBundle\Entity\Zipcode as ZipcodeEntity;
use AppBundle\Repository\ZipcodeRepository;
use AppBundle\Services\Zipcode;

/**
 * @group unit
 */
class ZipcodeTest extends AbstractServicesTest
{
    /**
     * @var ZipcodeRepository
     */
    protected $zipcodeRepository;

    /**
     * @var ZipcodeEntity
     */
    protected $defaultZipcodeEntity;

    public function setUp()
    {
        parent::setUp();
        $this->zipcodeRepository = $this->getMockBuilder(ZipcodeRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findAll', 'find'])
            ->getMock();

        $this->defaultZipcodeEntity = new ZipcodeEntity('01623', 'Lommatzsch');
    }

    public function testFindWhenServiceIsFoundReturnsService()
    {
        $this->zipcodeRepository
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue($this->defaultZipcodeEntity))
            ->with('01623');

        $zipcode = new Zipcode($this->zipcodeRepository, $this->entityManager);
        $this->assertEquals($this->defaultZipcodeEntity, $zipcode->find('01623'));
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage city: The city must have at least 5 characters
     */
    public function testCreateZipcodeWithInvalidCityThrowsBadRequestHttpException()
    {
        $this->zipcodeRepository
            ->expects($this->never())
            ->method('find');
        $this->entityManager
            ->expects($this->never())
            ->method('persist');
        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $zipcode = new Zipcode($this->zipcodeRepository, $this->entityManager);
        $zipcode->create(new ZipcodeEntity('12345', 'ab'));
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage id: This value should have exactly 5 characters.
     */
    public function testCreateZipcodeWithInvalidIdThrowsBadRequestHttpException()
    {
        $this->zipcodeRepository
            ->expects($this->never())
            ->method('find');
        $this->entityManager
            ->expects($this->never())
            ->method('persist');
        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $zipcode = new Zipcode($this->zipcodeRepository, $this->entityManager);
        $zipcode->create(new ZipcodeEntity('123456', 'city'));
    }

    public function testCreateWithValidZipcodeReturnsPersistedZipcode()
    {
        $this->zipcodeRepository
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue(null))
            ->with('01623');
        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->defaultZipcodeEntity);
        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $zipcode = new Zipcode($this->zipcodeRepository, $this->entityManager);
        $zipcode->create($this->defaultZipcodeEntity);
    }
}
