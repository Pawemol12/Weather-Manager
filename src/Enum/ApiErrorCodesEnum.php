<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Error codes from api
 *
 * @author Paweł Lodzik <Pawemol12@gmail.com>
 */
abstract class ApiErrorCodesEnum {
    const CITY_NOT_FOUND = 404;
    const WRONG_API_KEY = 401;

    const RESPONSE_OK = 200;
}
