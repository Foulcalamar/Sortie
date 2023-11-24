<?php

namespace App\Data;

use App\Entity\Campus;
use DateTimeInterface;

class SortieRecherche
{
    public ?Campus $campus;

    public ?string $cle;

    public ?DateTimeInterface $dateFrom;

    public ?DateTimeInterface $dateTo;

    public ?bool $organisateurRecherche;

    public ?bool $inscritsRecherche;

    public ?bool $noninscritsRecherche;

    public ?bool $pasee;

}