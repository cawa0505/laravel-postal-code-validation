<?php

namespace Axlon\PostalCodeValidation\Support;

use Illuminate\Support\Arr;

class Constraint
{
    /**
     * The parameters.
     *
     * @var string[]
     */
    protected $parameters;

    /**
     * The rule name.
     *
     * @var string
     */
    protected $rule;

    /**
     * Create a new postal code rule instance.
     *
     * @param string $rule
     * @param string|string[] ...$parameters
     * @return void
     */
    public function __construct(string $rule, ...$parameters)
    {
        $this->parameters = Arr::flatten($parameters);
        $this->rule = $rule;
    }

    /**
     * Convert the rule to a validation string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->rule . ':' . implode(',', $this->parameters);
    }
}
