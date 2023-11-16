<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $campus = new Campus();
        $campus->setNom('NANTES');
        $manager->persist($campus);

        $campus = new Campus();
        $campus->setNom('RENNES');
        $manager->persist($campus);

        $campus = new Campus();
        $campus->setNom('QUIMPER');
        $manager->persist($campus);

        $campus = new Campus();
        $campus->setNom('NIORT');
        $manager->persist($campus);


        $manager->flush();

    }
}