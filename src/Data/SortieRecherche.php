<?php

namespace App\Data;

use App\Entity\Campus;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SortieRecherche
{
    public ?Campus $campus;

    public ?string $cle;

    public ?DateType $dateFrom;

    public ?DateType $dateTo;

    public ?bool $organisateurRecherche;

    public ?bool $inscritsRecherche;

    public ?bool $noninscritsRecherche;

    public ?bool $pasee;

}