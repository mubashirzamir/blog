<?php

namespace App\Providers;

use App\Services\TenantAwareTranslatorService;
use App\Services\TranslationKeyModifierService;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\FileLoader;

class TenantAwareTranslationProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerLoader();

        $this->app->singleton(Translator::class, function ($app) {
            $loader = $app['translation.loader'];
            $locale = $app->getLocale();

            $trans = new TenantAwareTranslatorService($loader, $locale);
            $trans->setFallback($app->getFallbackLocale());
            $trans->setKeyModifierService($app->make(TranslationKeyModifierService::class));

            return $trans;
        });

        $this->app->alias(Translator::class, 'translator');
    }

    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new FileLoader($app['files'], [__DIR__ . '/lang', $app['path.lang']]);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
