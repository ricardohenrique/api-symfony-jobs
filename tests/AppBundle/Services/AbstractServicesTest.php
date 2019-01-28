<?php

namespace Tests\AppBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Tests\Functional\WebTestCase;

abstract class AbstractServicesTest extends WebTestCase
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function setUp()
    {
        $this->entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}