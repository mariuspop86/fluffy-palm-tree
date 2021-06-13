<?php

namespace App\Enum;

class CustomerEnum
{
    public const JOHN = 'John';
    public const DOE = 'Doe';
    public const LUKE = 'Luke';
    public const SKYWALKER = 'Skywalker';
    public const CUSTOMERS = [
        self::JOHN,
        self::DOE,
        self::LUKE,
        self::SKYWALKER,
    ];
}
