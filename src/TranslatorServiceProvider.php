<?php

namespace LaravelUtility\Translator;

use App\Console\Commands\PrepareTranslator,
    Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;


class TranslatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/translator.php' => config_path('translator.php'),
        ], 'translator');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.translator', function () {
            return new PrepareTranslator(new Filesystem);
        });
        $this->commands(['command.translator']);
        
        require_once(__DIR__. '/Helpers/translator_helper.php');

        $this->mergeConfigFrom(__DIR__.'/config/translator.php', 'translator');
    }
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['command.translator'];
    }
}
