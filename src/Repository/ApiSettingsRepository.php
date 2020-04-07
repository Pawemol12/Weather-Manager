<?php

namespace App\Repository;

use App\Entity\ApiSettings;
use Doctrine\Persistence\ManagerRegistry;

class ApiSettingsRepository extends CoreRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiSettings::class);
    }
}
