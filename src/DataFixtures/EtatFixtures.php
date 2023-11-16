<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtatFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $etat = new Etat();
        $etat->setLibelle('Brouillon');
        $manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('Créée');
        $manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('Ouverte');
        $manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('Clôturée');
        $manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('Activité en cours');
        $manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('Passée');
        $manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('Annulée');
        $manager->persist($etat);


        $manager->flush();
    }
}