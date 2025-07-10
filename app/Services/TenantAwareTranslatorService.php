<?php

namespace App\Services;

use App\Helpers\Tenant;
use Illuminate\Contracts\Translation\Loader;
use Illuminate\Translation\Translator;


enum Strategy: string
{
    case ARRAYS = 'arrays';
    case FOLDERS = 'files';
    case DEFAULT = 'default';

}

class TenantAwareTranslatorService extends Translator
{
    const FOLDER_NAME = 'tenants';
    const ARRAY_PREFIX = 'tenant_';

    public string $strategy;
    public string $folderName;
    public string $arrayPrefix;

    public function __construct(Loader $loader, $locale)
    {
        parent::__construct($loader, $locale);

        $this->strategy = env('LOCALIZATION_STRATEGY', Strategy::ARRAYS->value);
        $this->folderName = env('LOCALIZATION_TENANT_FOLDER_NAME', self::FOLDER_NAME);
        $this->arrayPrefix = env('LOCALIZATION_TENANT_ARRAY_PREFIX', self::ARRAY_PREFIX);
    }

    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        $tenantKey = $this->tenantKey($key);

        // First try tenant-specific key
        $result = parent::get($tenantKey, $replace, $locale, $fallback);

        // If it wasn't found, fallback to the original key
        if ($result === $tenantKey) {
            $result = parent::get($key, $replace, $locale, $fallback);
        }

        return $result;
    }

    public function tenantKey(string $key): string
    {
        return match ($this->strategy) {
            Strategy::ARRAYS->value => $this->arrayKey($key),
            Strategy::FOLDERS->value => $this->folderKey($key),
            default => $key,
        };
    }

    private function arrayKey(string $key): string
    {
        // find the last dot in the key and insert  $this->arrayPrefix . Tenant::name() after it
        $lastDotPosition = strrpos($key, '.');

        if ($lastDotPosition !== false) {
            return substr($key, 0, $lastDotPosition + 1) . $this->arrayPrefix . Tenant::name() . '.' . substr($key, $lastDotPosition + 1);
        }

        return "";
    }

    private function folderKey(string $key): string
    {
        return $this->folderName . '/' . Tenant::name() . '/' . $key;
    }
}
