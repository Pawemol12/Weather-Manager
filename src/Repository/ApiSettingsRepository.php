<?php

namespace App\Repository;

use App\Entity\ApiSettings;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @author Paweł Lodzik <Pawemol12@gmail.com>
 */
class ApiSettingsRepository extends CoreRepository
{
    /**
     * ApiSettingsRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiSettings::class);
    }
}
