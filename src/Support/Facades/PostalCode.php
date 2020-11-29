<?php

namespace Axlon\PostalCodeValidation\Support\Facades;

use Axlon\PostalCodeValidation\Support\Constraint;
use Illuminate\Support\Facades\Facade;

/**
 * @method static bool fails(string $countryCode, string ...$postalCodes)
 * @method static bool passes(string $countryCode, string ...$postalCodes)
 * @method static void override(array|string $key, string|null $pattern = null)
 * @method static bool supports(string $countryCode)
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
