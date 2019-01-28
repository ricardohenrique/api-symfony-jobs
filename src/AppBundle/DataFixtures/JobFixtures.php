<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use DateTime;

class JobFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $job = new Job(
            804040,
            '10115',
            'title',
            'decription',
            new DateTime('2018-11-11')
        );
        $manager->persist($job);
        $manager->flush();
    }
}
