<?php

namespace Axlon\PostalCodeValidation\Tests\Validation;

use Axlon\PostalCodeValidation\Support\Facades\PostalCode;
use Axlon\PostalCodeValidation\Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class PostalCodeWithTest extends TestCase
{
    public function testItFailsWhenOtherIsInvalid(): void
    {
        $data = ['value' => '95014', 'country' => 'United States'];
        $rules = ['value' => 'postal_code_with:country'];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid postal code.', $validator->errors()->all());
    }

    public function testItFailsWhenOtherIsNotStringOrInt(): void
    {
        $data = ['value' => '95014', 'country' => ['US']];
        $rules = ['value' => 'postal_code_with:country'];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid postal code.', $validator->errors()->all());
    }

    public function testItFailsWhenOtherIsNull(): void
    {
        $data = ['value' => '95014', 'country' => null];
        $rules = ['value' => 'postal_code_with:country'];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid postal code.', $validator->errors()->all());
    }

    public function testItFailsWhenOthersAreMissing(): void
    {
        $data = ['value' => '95014'];
        $rules = ['value' => 'postal_code_with:country'];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid postal code.', $validator->errors()->all());
    }

    public function testItFailsWhenValueIsInvalid(): void
    {
        $data = ['value' => 'invalid', 'country' => 'US'];
        $rules = ['value' => 'postal_code_with:country'];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid postal code.', $validator->errors()->all());
    }

    public function testItFailsWhenValueIsInvalidInArray(): void
    {
        $data = ['values' => ['95014', 'invalid'], 'countries' => ['US', 'US']];
        $rules = ['values.*' => 'postal_code_with:countries.*'];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());
        $this->assertNotContains('The values.0 must be a valid postal code.', $validator->errors()->all());
        $this->assertContains('The values.1 must be a valid postal code.', $validator->errors()->all());
    }

    public function testItFailsWhenValueIsInvalidWithOverride(): void
    {
        PostalCode::override(['US' => '/(?!)/']);

        $data = ['value' => '95014', 'country' => 'US'];
        $rules = ['value' => 'postal_code_with:country'];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid postal code.', $validator->errors()->all());
    }

    public function testItFailsWhenValueIsNotStringOrInt(): void
    {
        $data = ['value' => ['95014'], 'country' => 'US'];
        $rules = ['value' => 'postal_code_with:country'];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid postal code.', $validator->errors()->all());
    }

    /** @link https://github.com/axlon/laravel-postal-code-validation/issues/23 */
    public function testItFailsWhenValueIsNull(): void
    {
        $data = ['value' => null, 'country' => 'US'];
        $rules = ['value' => 'postal_code_with:country'];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid postal code.', $validator->errors()->all());
    }

    public function testItPassesWhenValueIsValid(): void
    {
        $data = ['value' => '95014', 'country' => 'US'];
        $rules = ['value' => 'postal_code_with:country'];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->passes());
    }

    public function testItPassesWhenValueIsValidAndOtherIsLowercase(): void
    {
        $data = ['value' => '95014', 'country' => 'us'];
        $rules = ['value' => 'postal_code_with:country'];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->passes());
    }

    public function testItPassesWhenValueIsValidForAnyOther(): void
    {
        $data = ['value' => '95014', 'country1' => 'CA', 'country2' => 'US'];
        $rules = ['value' => 'postal_code_with:country1,country2'];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->passes());
    }

    public function testItPassesWhenValueIsValidInArray(): void
    {
        $data = ['values' => ['95014'], 'countries' => ['US']];
        $rules = ['values.*' => 'postal_code_with:countries.*'];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->passes());
    }

    public function testItReplacesCountriesInErrorMessage(): void
    {
        $data = ['value' => null, 'country' => 'US'];
        $messages = ['value.postal_code_with' => 'The :attribute must be a valid :countries postal code.'];
        $rules = ['value' => 'postal_code_with:country'];

        $validator = Validator::make($data, $rules, $messages);

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid US postal code.', $validator->errors()->all());
    }

    public function testItReplacesExamplesInErrorMessage(): void
    {
        $data = ['value' => null, 'country' => 'US'];
        $messages = ['value.postal_code_with' => 'The :attribute must be a valid postal code (e.g. :examples).'];
        $rules = ['value' => 'postal_code_with:country'];

        $validator = Validator::make($data, $rules, $messages);

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid postal code (e.g. 95014).', $validator->errors()->all());
    }

    public function testItThrowsWhenItReceivesNoParameters(): void
    {
        $data = ['value' => '95014', 'country' => 'US'];
        $rules = ['value' => 'postal_code_with'];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Validation rule postal_code_with requires at least 1 parameter.');

        Validator::validate($data, $rules);
    }
}
