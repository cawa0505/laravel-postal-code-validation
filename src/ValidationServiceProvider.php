<?php

namespace Axlon\PostalCodeValidation;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register postal code validation services.
     *
     * @return void
     */
    public function register(): void
    {
        if ($this->app->resolved('validator')) {
            $this->registerRules($this->app['validator']);
        } else {
            $this->app->resolving('validator', function (Factory $validator) {
                $this->registerRules($validator);
            });
        }

        $this->app->singleton('postal_codes', function () {
            return new PostalCodeValidator(require __DIR__ . '/../resources/patterns.php');
        });

        $this->app->alias('postal_codes', PostalCodeValidator::class);
    }

    /**
     * Register the postal code validation rules with the validator.
     *
     * @param \Illuminate\Validation\Factory $validator
     * @return void
     */
    public function registerRules(Factory $validator): void
    {
        $validator->extend(
            'postal_code',
            'Axlon\PostalCodeValidation\Extensions\PostalCode@validate',
            'The :attribute must be a valid postal code.'
        );

        $validator->extendDependent(
            'postal_code_with',
            'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@validate',
            'The :attribute must be a valid postal code.'
        );

        $validator->replacer('postal_code', 'Axlon\PostalCodeValidation\Extensions\PostalCode@replace');
        $validator->replacer('postal_code_with', 'Axlon\PostalCodeValidation\Extensions\PostalCodeFor@replace');
    }
}
