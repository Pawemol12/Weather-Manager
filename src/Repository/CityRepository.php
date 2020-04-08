<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @author Paweł Lodzik <Pawemol12@gmail.com>
 */
class CityRepository extends CoreRepository
{
    /**
     * CityRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }
}
