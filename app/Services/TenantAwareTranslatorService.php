<?php

namespace App\Services;

use App\Helpers\Tenant;
use Illuminate\Contracts\Translation\Loader;
use Illuminate\Translation\Translator;


class TenantAwareTranslatorService extends Translator
{
    const FOLDER = 'tenants';

    public function __construct(Loader $loader, $locale)
    {
        parent::__construct($loader, $locale);
    }

    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        $tenantKey = self::FOLDER . '/' . Tenant::name() . '/' . $key;

        // First try tenant-specific key
        $result = parent::get($tenantKey, $replace, $locale, $fallback);

        // If it wasn't found, fallback to the original key
        if ($result === $tenantKey) {
            $result = parent::get($key, $replace, $locale, $fallback);
        }

        return $result;
    }
}
