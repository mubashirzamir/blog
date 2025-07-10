<?php

namespace App\Services;

use Illuminate\Contracts\Translation\Loader;
use Illuminate\Translation\Translator;

class TenantAwareTranslatorService extends Translator
{
    public TranslationKeyModifierService $keyModifierService;

    public function __construct(Loader $loader, $locale)
    {
        parent::__construct($loader, $locale);
    }

    public function setKeyModifierService(TranslationKeyModifierService $keyModifierService): void
    {
        $this->keyModifierService = $keyModifierService;
    }

    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        $tenantKey = $this->keyModifierService->tenantKey($key);

        // First try tenant-specific key
        $result = parent::get($tenantKey, $replace, $locale, $fallback);

        // If it wasn't found, fallback to the original key
        if ($result === $tenantKey) {
            $result = parent::get($key, $replace, $locale, $fallback);
        }

        return $result;
    }
}
