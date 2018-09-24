<?php

namespace App\Providers;

use Illuminate\Database\Schema\Builder as Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory as Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @param Validator $validator
     * @param Schema $schema
     */
    public function boot(Validator $validator, Schema $schema)
    {
        $schema->defaultStringLength(191);

        $validator->extend('image64', function ($attribute, $value, $params, $validator) {
            $mimeType =  explode(':', substr($value, 0, strpos($value, ';')))[1];
            $type = explode('/', $mimeType)[1];

            if (in_array($type, $params)) {
                return true;
            }

            return false;
        });

        $validator->replacer('image64', function ($message, $attribute, $rule, $params) {
            return str_replace(':values', join(",", $params), $message);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
