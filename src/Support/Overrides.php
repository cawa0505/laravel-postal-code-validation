<?php

namespace Axlon\PostalCodeValidation\Support;

class Overrides
{
    /**
     * The overrides.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Add validation override(s).
     *
     * @param array|string $key
     * @param string|null $pattern
     * @return void
     */
    public function add($key, ?string $pattern = null): void
    {
        if (is_array($key)) {
            $this->data = array_merge(
                $this->data,
                array_change_key_case($key, CASE_UPPER)
            );
        } else {
            $this->data[strtoupper($key)] = $pattern;
        }
    }

    /**
     * Get a validation override.
     *
     * @param string $key
     * @return string
     */
    public function get(string $key): string
    {
        return $this->data[strtoupper($key)];
    }

    /**
     * Determine whether a validation override exists.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists(strtoupper($key), $this->data);
    }
}
