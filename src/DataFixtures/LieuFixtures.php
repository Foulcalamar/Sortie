<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LieuFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        $villeRepository = $manager->getRepository(Ville::class);
        $villes = $villeRepository->findAll();

        for ($i = 1; $i <= 50; $i++) {
            $lieu = new Lieu();
            $lieu->setNom($faker->words(3, true));
            $lieu->setRue($faker->streetName());
            $lieu->setLatitude($faker->latitude(42, 51));
            $lieu->setLongitude($faker->longitude(2, 8));
            $lieu->setVille($faker->randomElement($villes));
            $manager->persist($lieu);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            VilleFixtures::class,
        ];
    }
}