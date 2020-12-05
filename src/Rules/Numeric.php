<?php

namespace Axlon\PostalCodeValidation\Rules;

use Axlon\PostalCodeValidation\Contracts\Rules;
use League\ISO3166\Exception\ISO3166Exception;
use League\ISO3166\ISO3166;

class Numeric implements Rules
{
    /**
     * The ISO 3166-1 data.
     *
     * @var \League\ISO3166\ISO3166
     */
    protected $countries;

    /**
     * The validation rules.
     *
     * @var \Axlon\PostalCodeValidation\Rules\Alpha2
     */
    protected $rules;

    /**
     * Create a new ISO 3166-1 numeric rule set.
     *
     * @param \Axlon\PostalCodeValidation\Rules\Alpha2 $rules
     * @param \League\ISO3166\ISO3166 $countries
     * @return void
     */
    public function __construct(Alpha2 $rules, ISO3166 $countries)
    {
        $this->countries = $countries;
        $this->rules = $rules;
    }

    /**
     * Convert a ISO 3166-1 numeric code to alpha-2 format.
     *
     * @param string $key
     * @return string
     * @throws \League\ISO3166\Exception\ISO3166Exception
     */
    protected function convert(string $key): string
    {
        return $this->countries->numeric($key)['alpha2'];
    }

    /**
     * @inheritDoc
     * @throws \League\ISO3166\Exception\ISO3166Exception
     */
    public function get(string $key): string
    {
        return $this->rules->get($this->convert($key));
    }

    /**
     * @inheritDoc
     * @throws \League\ISO3166\Exception\ISO3166Exception
     */
    public function getExample(string $key): ?string
    {
        return $this->rules->getExample($this->convert($key));
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        try {
            return $this->rules->has($this->convert($key));
        } catch (ISO3166Exception $e) {
            return false;
        }
    }
}
