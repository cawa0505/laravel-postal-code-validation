<?php

namespace Axlon\PostalCodeValidation\Support\Facades;

use Axlon\PostalCodeValidation\Rules\Alpha2;
use Axlon\PostalCodeValidation\Rules\Alpha3;
use Axlon\PostalCodeValidation\Rules\Numeric;
use Axlon\PostalCodeValidation\Support\Constraint;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void override(array|string $key, string|null $pattern = null)
 * @method static void use(\Axlon\PostalCodeValidation\Contracts\Rules $rules)
 *
 * @see \Axlon\PostalCodeValidation\PostalCodeValidator
 */
class PostalCode extends Facade
{
    /**
     * Get a postal_code constraint builder instance.
     *
     * @param string|string[] ...$parameters
     * @return \Axlon\PostalCodeValidation\Support\Constraint
     */
    public static function for(...$parameters): Constraint
    {
        return new Constraint('postal_code', $parameters);
    }

    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor(): string
    {
        return 'postal_codes';
    }

    /**
     * Validate using ISO 3166-1 alpha-2 country codes.
     *
     * @return void
     */
    public static function useAlpha2(): void
    {
        static::use(static::getFacadeApplication()->make(Alpha2::class));
    }

    /**
     * Validate using ISO 3166-1 alpha-3 country codes.
     *
     * @return void
     */
    public static function useAlpha3(): void
    {
        static::use(static::getFacadeApplication()->make(Alpha3::class));
    }

    /**
     * Validate using ISO 3166-1 alpha-3 country codes.
     *
     * @return void
     */
    public static function useNumeric(): void
    {
        static::use(static::getFacadeApplication()->make(Numeric::class));
    }

    /**
     * Validate that the value is a valid postal code.
     *
     * @param string $value
     * @param string|string[] ...$parameters
     * @return bool
     */
    public static function validate(string $value, ...$parameters): bool
    {
        return static::getFacadeRoot()->validatePostalCode('attribute', $value, Arr::flatten($parameters));
    }

    /**
     * Get a postal_code_with constraint builder instance.
     *
     * @param string|string[] ...$parameters
     * @return \Axlon\PostalCodeValidation\Support\Constraint
     */
    public static function with(...$parameters): Constraint
    {
        return new Constraint('postal_code_with', $parameters);
    }
}
