<?php

namespace App\Services;

use App\Helpers\Tenant;
use Exception;

enum Strategy: string
{
    case ARRAYS = 'arrays';
    case FOLDERS = 'folders';
    case DEFAULT = 'default';
}

class TranslationKeyModifierService
{
    const FOLDER_NAME = 'tenants';
    const ARRAY_PREFIX = 'tenant_';

    public string $strategy;
    public string $folderName;
    public string $arrayPrefix;

    public function __construct(string $strategy = Strategy::ARRAYS->value, string $folderName = self::FOLDER_NAME, string $arrayPrefix = self::ARRAY_PREFIX)
    {
        $this->strategy = env('TRANSLATION_STRATEGY', $strategy);
        $this->folderName = env('TRANSLATION_TENANT_FOLDER_NAME', $folderName);
        $this->arrayPrefix = env('TRANSLATION_TENANT_ARRAY_PREFIX', $arrayPrefix);
    }

    public function tenantKey(string $key): string
    {
        return match ($this->strategy) {
            Strategy::ARRAYS->value => $this->arrayKey($key),
            Strategy::FOLDERS->value => $this->folderKey($key),
            default => $key,
        };
    }

    /**
     * @throws Exception
     */
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
        return implode('/', [$this->folderName, Tenant::name(), $key]);
    }
}
