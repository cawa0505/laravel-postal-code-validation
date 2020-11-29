<?php

namespace Axlon\PostalCodeValidation\Tests\Validation;

use Axlon\PostalCodeValidation\Support\Facades\PostalCode;
use Axlon\PostalCodeValidation\Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class PostalCodeTest extends TestCase
{
    public function testItFailsWhenParameterIsInvalid(): void
    {
        $data = ['value' => '1234 AB'];
        $rules = ['value' => 'postal_code:Netherlands'];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid postal code.', $validator->errors()->all());
    }

    public function testItFailsWhenValueIsInvalid(): void
    {
        $data = ['value' => 'invalid'];
        $rules = ['value' => 'postal_code:NL'];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid postal code.', $validator->errors()->all());
    }

    public function testItFailsWhenValueIsInvalidWithOverride(): void
    {
        PostalCode::override('NL', '/(?!)/');

        $data = ['value' => '1234 AB'];
        $rules = ['value' => 'postal_code:NL'];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid postal code.', $validator->errors()->all());
    }

    public function testItFailsWhenValueIsNotStringOrInt(): void
    {
        $data = ['value' => ['1234 AB']];
        $rules = ['value' => 'postal_code:NL'];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid postal code.', $validator->errors()->all());
    }

    /** @link https://github.com/axlon/laravel-postal-code-validation/issues/23 */
    public function testItFailsWhenValueIsNull(): void
    {
        $data = ['value' => null];
        $rules = ['value' => 'postal_code:NL'];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid postal code.', $validator->errors()->all());
    }

    public function testItPassesWhenValueIsValid(): void
    {
        $data = ['value' => '1234 AB'];
        $rules = ['value' => 'postal_code:NL'];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->passes());
    }

    public function testItPassesWhenValueIsValidAndParameterIsLowercase(): void
    {
        $data = ['value' => '1234 AB'];
        $rules = ['value' => 'postal_code:nl'];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->passes());
    }

    public function testItPassesWhenValueIsValidForAnyParameter(): void
    {
        $data = ['value' => '1234 AB'];
        $rules = ['value' => 'postal_code:BE,NL'];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->passes());
    }

    public function testItReplacesCountriesInErrorMessage(): void
    {
        $data = ['value' => null];
        $messages = ['value.postal_code' => 'The :attribute must be a valid :countries postal code.'];
        $rules = ['value' => 'postal_code:NL'];

        $validator = Validator::make($data, $rules, $messages);

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid NL postal code.', $validator->errors()->all());
    }

    public function testItReplacesExamplesInErrorMessage(): void
    {
        $data = ['value' => null];
        $messages = ['value.postal_code' => 'The :attribute must be a valid postal code (e.g. :examples).'];
        $rules = ['value' => 'postal_code:NL'];

        $validator = Validator::make($data, $rules, $messages);

        $this->assertFalse($validator->passes());
        $this->assertContains('The value must be a valid postal code (e.g. 1234 AB).', $validator->errors()->all());
    }

    public function testItThrowsWhenItReceivesNoParameters(): void
    {
        $data = ['value' => '1234 AB'];
        $rules = ['value' => 'postal_code'];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Validation rule postal_code requires at least 1 parameter.');

        Validator::validate($data, $rules);
    }
}
