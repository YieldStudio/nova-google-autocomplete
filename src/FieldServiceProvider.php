<?php

namespace YieldStudio\NovaGoogleAutocomplete;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/lang' => lang_path('/vendor/nova-google-autocomplete'),
            ], 'nova-google-autocomplete-lang');

            $this->publishes([
                __DIR__.'/../config' => config_path(),
            ], 'nova-google-autocomplete-config');
        }

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'nova-google-autocomplete');
        $this->loadJsonTranslationsFrom(lang_path('/vendor/nova-google-autocomplete'));

        Nova::serving(function (ServingNova $event) {
            Nova::script('google-autocomplete', __DIR__.'/../dist/js/field.js');
            Nova::remoteScript('https://maps.googleapis.com/maps/api/js?key='.config('nova-google-autocomplete.api_key').'&libraries=places');
            Nova::translations(lang_path('/vendor/nova-google-autocomplete/'.app()->getLocale().'.json'));
        });
    }
}
