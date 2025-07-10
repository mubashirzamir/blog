<?php

namespace App\Helpers;

class Tenant
{
    public static function name(): string
    {
        return env('TENANT_NAME', 'default');
    }
}
