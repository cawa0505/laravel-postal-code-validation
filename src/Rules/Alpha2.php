<?php

namespace Axlon\PostalCodeValidation\Rules;

use Axlon\PostalCodeValidation\Contracts\Rules;

class Alpha2 implements Rules
{
    /**
     * The validation data.
     *
     * @var array
     */
    protected $data;

    /**
     * Create a new ISO 3166-1 alpha-2 rule set.
     *
     * @return void
     */
    public function __construct()
    {
        $this->data = require __DIR__ . '/../../resources/countries.php';
    }

    /**
     * Compile the validation pattern to a regular expression.
     *
     * @param string $pattern
     * @return string
     */
    protected function compilePattern(string $pattern): string
    {
        return sprintf('/^(?:%s)$/', $pattern);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): string
    {
        $key = strtoupper($key);

        return isset($this->data[$key][0])
            ? $this->compilePattern($this->data[$key][0])
            : '/.*/';
    }

    /**
     * @inheritDoc
     */
    public function getExample(string $key): ?string
    {
        return $this->data[strtoupper($key)][1] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return array_key_exists(strtoupper($key), $this->data);
    }
}
