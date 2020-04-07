<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Persistence\ManagerRegistry;


class CityRepository extends CoreRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

}
