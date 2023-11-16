<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SortieFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        $lieuRepository = $manager->getRepository(Lieu::class);
        $lieux = $lieuRepository->findAll();

        $etatRepository = $manager->getRepository(Etat::class);
        $etats = $etatRepository->findAll();

        $campusRepository = $manager->getRepository(Campus::class);
        $campus = $campusRepository->findAll();

        $participantRepository = $manager->getRepository(Participant::class);
        $participants = $participantRepository->findAll();

        for ($i = 1; $i <= 50; $i++) {
            $sortie = new Sortie();
            $sortie->setNom($faker->word());
            $sortie->setDateHeureDebut($faker->dateTimeBetween('now', '+10 years'));
            $sortie->setDuree(rand(10, 180));
            $sortie->setDateLimiteInscription($faker->dateTimeBetween('-60 days', $sortie->getDateHeureDebut()));
            $sortie->setNbInscriptionsMax(rand(3, 500));
            $sortie->setInfosSortie($faker->text(300));
            $sortie->setLieu($faker->randomElement($lieux));
            $sortie->setEtat($faker->randomElement($etats));
            $sortie->setCampus($faker->randomElement($campus));
            $sortie->setParticipantOrganisateur($faker->randomElement($participants));
            $manager->persist($sortie);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LieuFixtures::class, EtatFixtures::class, CampusFixtures::class, ParticipantFixtures::class,
        ];
    }
}