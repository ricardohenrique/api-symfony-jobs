<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Zipcode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ZipcodeFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $zipcode1 = new Zipcode('10115', 'Berlin');
        $zipcode2 = new Zipcode('32457', 'Porta Westfalica');
        $zipcode3 = new Zipcode('01623', 'Lommatzsch');
        $zipcode4 = new Zipcode('21521', 'Hamburg');
        $zipcode5 = new Zipcode('06895', 'Bülzig');
        $zipcode6 = new Zipcode('01612', 'Diesbar-Seußlitz');
        $manager->persist($zipcode1);
        $manager->persist($zipcode2);
        $manager->persist($zipcode3);
        $manager->persist($zipcode4);
        $manager->persist($zipcode5);
        $manager->persist($zipcode6);
        $manager->flush();
    }
}