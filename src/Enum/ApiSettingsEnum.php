<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * User Roles Enum
 * 
 * @author Paweł Lodzik <Pawemol12@gmail.com>
 */
abstract class ApiSettingsEnum {
    const API_KEY = 'API_KEY';
    const WEATHER_INFO_BY_CITY_LINK = 'WEATHER_INFO_BY_CITY_LINK';
    const WEATHER_INFO_BY_CITY_AND_STATE_LINK = 'WEATHER_INFO_BY_CITY_AND_STATE_LINK';
    const WEATHER_INFO_BY_CITY_ID_LINK = 'WEATHER_INFO_BY_CITY_ID_LINK';
}
