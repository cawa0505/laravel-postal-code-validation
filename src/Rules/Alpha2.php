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
     * The examples.
     *
     * @var array
     */
    protected $examples;

    /**
     * Create a new ISO 3166-1 alpha-2 rule set.
     *
     * @return void
     */
    public function __construct()
    {
        $this->data = require __DIR__ . '/../../resources/patterns.php';
        $this->examples = require __DIR__ . '/../../resources/examples.php';
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): string
    {
        return $this->data[strtoupper($key)] ?? '/.*/';
    }

    /**
     * @inheritDoc
     */
    public function getExample(string $key): ?string
    {
        return $this->examples[strtoupper($key)] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return array_key_exists(strtoupper($key), $this->data);
    }
}
