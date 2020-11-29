<?php

namespace Axlon\PostalCodeValidation;

use Axlon\PostalCodeValidation\Support\Overrides;

class PostalCodeValidator
{
    /**
     * The validation overrides.
     *
     * @var \Axlon\PostalCodeValidation\Support\Overrides
     */
    protected $overrides;

    /**
     * The matching patterns.
     *
     * @var array
     */
    protected $patterns;

    /**
     * Create a new postal code matcher.
     *
     * @param array $patterns
     * @param \Axlon\PostalCodeValidation\Support\Overrides $overrides
     * @return void
     */
    public function __construct(array $patterns, Overrides $overrides)
    {
        $this->overrides = $overrides;
        $this->patterns = $patterns;
    }

    /**
     * Determine if the given postal code(s) are invalid for the given country.
     *
     * @param string $countryCode
     * @param string|null ...$postalCodes
     * @return bool
     */
    public function fails(string $countryCode, ?string ...$postalCodes): bool
    {
        return !$this->passes($countryCode, ...$postalCodes);
    }

    /**
     * Override pattern matching for the given country.
     *
     * @param array|string $key
     * @param string|null $pattern
     * @return void
     */
    public function override($key, ?string $pattern = null): void
    {
        $this->overrides->add($key, $pattern);
    }

    /**
     * Determine if the given postal code(s) are valid for the given country.
     *
     * @param string $countryCode
     * @param string|null ...$postalCodes
     * @return bool
     */
    public function passes(string $countryCode, ?string ...$postalCodes): bool
    {
        if (!$this->supports($countryCode)) {
            return false;
        }

        if (($pattern = $this->patternFor($countryCode)) === null) {
            return true;
        }

        foreach ($postalCodes as $postalCode) {
            if ($postalCode === null || trim($postalCode) === '') {
                return false;
            }

            if (preg_match($pattern, $postalCode) !== 1) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the matching pattern for the given country.
     *
     * @param string $countryCode
     * @return string|null
     */
    public function patternFor(string $countryCode): ?string
    {
        $countryCode = strtoupper($countryCode);

        if ($this->overrides->has($countryCode)) {
            return $this->overrides->get($countryCode);
        }

        return $this->patterns[$countryCode] ?? null;
    }

    /**
     * Determine if a matching pattern exists for the given country.
     *
     * @param string $countryCode
     * @return bool
     */
    public function supports(string $countryCode): bool
    {
        $countryCode = strtoupper($countryCode);

        return $this->overrides->has($countryCode)
            || array_key_exists($countryCode, $this->patterns);
    }
}
