<?php

namespace Axlon\PostalCodeValidation\Contracts;

interface Rules
{
    /**
     * Get a validation rule.
     *
     * @param string $key
     * @return string
     */
    public function get(string $key): string;

    /**
     * Get an example of valid input for the rule with given key.
     *
     * @param string $key
     * @return string|null
     */
    public function getExample(string $key): ?string;

    /**
     * Determine if a rule exists with the given key.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;
}
