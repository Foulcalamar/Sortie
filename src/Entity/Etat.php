<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtatRepository::class)]
class Etat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $libelle = ['Créée', 'Ouverte', 'Clôturée', 'Activité en cours', 'Passée', 'Annulée'];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): array
    {
        return $this->libelle;
    }

    public function setLibelle(array $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }
}
