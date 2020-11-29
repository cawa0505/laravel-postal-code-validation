<?php

namespace Axlon\PostalCodeValidation\Tests\Integration;

use Axlon\PostalCodeValidation\Tests\TestCase;
use InvalidArgumentException;

class PostalCodeWithTest extends TestCase
{
    /**
     * Test if the 'postal_code_with' rule fails on invalid countries.
     *
     * @return void
     */
    public function testValidationFailsInvalidCountry(): void
    {
        $validator = $this->app->make('validator')->make(
            ['value' => '1234 AB', 'country' => 'not-a-country'],
            ['value' => 'postal_code_with:country']
        );

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid postal code.', $validator->errors()->all());
    }

    /**
     * Test if the 'postal_code_with' rule fails invalid input.
     *
     * @return void
     */
    public function testValidationFailsInvalidPostalCode(): void
    {
        $validator = $this->app->make('validator')->make(
            ['value' => 'not-a-postal-code', 'country' => 'NL'],
            ['value' => 'postal_code_with:country']
        );

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid postal code.', $validator->errors()->all());
    }

    /**
     * Test if the 'postal_code_with' rule fails invalid input.
     *
     * @return void
     */
    public function testValidationFailsInvalidPostalCodeInArray(): void
    {
        $validator = $this->app->make('validator')->make(
            ['value' => ['not-a-postal-code'], 'countries' => ['NL']],
            ['value.*' => 'postal_code_with:countries.*']
        );

        $this->assertFalse($validator->passes());
        $this->assertContains('The value.0 must be a valid postal code.', $validator->errors()->all());
    }

    /**
     * Test if the 'postal_code' rule fails null input.
     *
     * @return void
     * @link https://github.com/axlon/laravel-postal-code-validation/issues/23
     */
    public function testValidationFailsNullPostalCode(): void
    {
        $validator = $this->app->make('validator')->make(
            ['value' => null, 'country' => 'DE'],
            ['value' => 'postal_code_with:country']
        );

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid postal code.', $validator->errors()->all());
    }

    public function testValidationPassesIfAllFieldsAreMissing(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => '1234 AB'],
            ['postal_code' => 'postal_code_with:country']
        );

        $this->assertTrue($validator->passes());
        $this->assertEmpty($validator->errors()->all());
    }

    /**
     * Test if the 'postal_code_with' rule ignores references that aren't present.
     *
     * @return void
     */
    public function testValidationIgnoresMissingFields(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => '1234 AB', 'empty' => '', 'null' => null, 'country' => 'NL'],
            ['postal_code' => 'postal_code_with:empty,missing,null,country']
        );

        $this->assertTrue($validator->passes());
        $this->assertEmpty($validator->errors()->all());
    }

    public function testValidationIgnoresMissingFieldsFailing(): void
    {
        $validator = $this->app->make('validator')->make(
            ['value' => '1234 AB', 'empty' => '', 'null' => null, 'country' => 'BE'],
            ['value' => 'postal_code_with:empty,missing,null,country']
        );

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid postal code.', $validator->errors()->all());
    }

    /**
     * Test if the 'postal_code_with' rule passes valid input.
     *
     * @return void
     */
    public function testValidationPassesValidPostalCode(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => '1234 AB', 'country' => 'NL'],
            ['postal_code' => 'postal_code_with:country']
        );

        $this->assertTrue($validator->passes());
        $this->assertEmpty($validator->errors()->all());
    }

    /**
     * Test if the 'postal_code_with' rule passes valid input.
     *
     * @return void
     */
    public function testValidationPassesValidPostalCodeInArray(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_codes' => ['1234 AB'], 'countries' => ['NL']],
            ['postal_codes.*' => 'postal_code_with:countries.*']
        );

        $this->assertTrue($validator->passes());
        $this->assertEmpty($validator->errors()->all());
    }

    /**
     * Test if an exception is thrown when calling the 'postal_code' rule without arguments.
     *
     * @return void
     */
    public function testValidationThrowsWithoutParameters(): void
    {
        $validator = $this->app->make('validator')->make(
            ['postal_code' => '1234 AB'],
            ['postal_code' => 'postal_code_with']
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Validation rule postal_code_with requires at least 1 parameter.');

        $validator->validate();
    }
}
