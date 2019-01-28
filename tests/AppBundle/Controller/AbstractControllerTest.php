<?php

namespace Tests\AppBundle\Controller;

use AppBundle\DataFixtures\JobFixtures;
use AppBundle\DataFixtures\ServiceFixtures;
use AppBundle\DataFixtures\ZipcodeFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractControllerTest extends WebTestCase
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
        $this->client = self::createClient();
    }

    protected function loadServiceFixtures()
    {
        $this->load(new ServiceFixtures());
    }

    protected function loadZipcodeFixtures()
    {
        $this->load(new ZipcodeFixtures());
    }

    protected function loadJobFixtures()
    {
        $this->load(new JobFixtures());
    }

    private function load(Fixture $fixture){
        $fixture->load($this->entityManager);
    }

    public function tearDown()
    {
        parent::tearDown();

        $purger = new ORMPurger($this->entityManager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $purger->purge();
    }
}
