<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ParticipantFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        $campusRepository = $manager->getRepository(Campus::class);
        $campus = $campusRepository->findAll();


        for ($i = 1; $i <=200; $i++){
            $participant = new Participant();

            $participant->setEmail($faker->unique()->email());
            $participant->setMotPasse($faker->password());
            $participant->setNom($faker->lastName());
            $participant->setPrenom($faker->firstName());
            $participant->setTelephone($faker->phoneNumber());
            $participant->setAdministrateur(rand(0,1));
            $participant->setActif(rand(0,1));
            $participant->setPseudo($faker->unique()->userName());
            $participant->setCampus($faker->randomElement($campus));

            $manager->persist($participant);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CampusFixtures::class,
        ];
    }
}
