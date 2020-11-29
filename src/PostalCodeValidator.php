<?php

namespace Axlon\PostalCodeValidation;

use Axlon\PostalCodeValidation\Contracts\Rules;
use Axlon\PostalCodeValidation\Support\Overrides;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\Validator;
use InvalidArgumentException;

class PostalCodeValidator
{
    /**
     * The validation overrides.
     *
     * @var \Axlon\PostalCodeValidation\Support\Overrides
     */
    protected $overrides;

    /**
     * The validation rules.
     *
     * @var \Axlon\PostalCodeValidation\Contracts\Rules
     */
    protected $rules;

    /**
     * Create a new postal code matcher.
     *
     * @param \Axlon\PostalCodeValidation\Contracts\Rules $rules
     * @param \Axlon\PostalCodeValidation\Support\Overrides $overrides
     * @return void
     */
    public function __construct(Rules $rules, Overrides $overrides)
    {
        $this->overrides = $overrides;
        $this->rules = $rules;
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
     * Prepare the other values for validation.
     *
     * @param array $attributes
     * @param \Illuminate\Validation\Validator $validator
     * @return array
     */
    protected function prepareOthers(array $attributes, Validator $validator): array
    {
        $others = array_map(function (string $attribute) use ($validator) {
            return Arr::get($validator->getData(), $attribute);
        }, $attributes);

        return array_map(function ($other) {
            return is_string($other) || is_int($other) ? $other : null;
        }, $others);
    }

    /**
     * Replace all place-holders for the postal_code rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param string[] $parameters
     * @return string
     */
    public function replacePostalCode(string $message, string $attribute, string $rule, array $parameters): string
    {
        $parameters = Collection::make($parameters)->filter()->mapWithKeys(function (string $parameter) {
            return [$parameter => $this->rules->getExample($parameter)];
        });

        $replace = [
            ':attribute' => $attribute,
            ':countries' => $parameters->keys()->implode(', '),
            ':examples' => $parameters->filter()->implode(', '),
        ];

        return str_replace(array_keys($replace), array_values($replace), $message);
    }

    /**
     * Replace all place-holders for the postal_code_with rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param string[] $parameters
     * @param \Illuminate\Validation\Validator $validator
     * @return string
     */
    public function replacePostalCodeWith(string $message, string $attribute, string $rule, array $parameters, Validator $validator): string
    {
        return $this->replacePostalCode($message, $attribute, $rule, $this->prepareOthers($parameters, $validator));
    }

    /**
     * Set the validation rules.
     *
     * @param \Axlon\PostalCodeValidation\Contracts\Rules $rules
     * @return void
     */
    public function use(Rules $rules): void
    {
        $this->rules = $rules;
    }

    /**
     * Validate that an attribute is a valid postal code.
     *
     * @param string $attribute
     * @param mixed $value
     * @param string[] $parameters
     * @return bool
     */
    public function validatePostalCode(string $attribute, $value, array $parameters): bool
    {
        if (empty($parameters)) {
            throw new InvalidArgumentException('Validation rule postal_code requires at least 1 parameter.');
        }

        if (!is_string($value) && !is_int($value)) {
            return false;
        }

        $parameters = array_filter($parameters);
        $value = strtoupper($value);

        foreach ($parameters as $parameter) {
            if ($this->overrides->has($parameter)) {
                $pattern = $this->overrides->get($parameter);
            } elseif ($this->rules->has($parameter)) {
                $pattern = $this->rules->get($parameter);
            }

            if (isset($pattern) && preg_match($pattern, $value) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate that an attribute is a valid postal code for specified countries.
     *
     * @param string $attribute
     * @param mixed $value
     * @param string[] $parameters
     * @param \Illuminate\Validation\Validator $validator
     * @return bool
     */
    public function validatePostalCodeWith(string $attribute, $value, array $parameters, Validator $validator): bool
    {
        if (empty($parameters)) {
            throw new InvalidArgumentException('Validation rule postal_code_with requires at least 1 parameter.');
        }

        return $this->validatePostalCode($attribute, $value, $this->prepareOthers($parameters, $validator));
    }
}
