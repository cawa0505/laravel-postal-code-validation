<?php

namespace Axlon\PostalCodeValidation\Tests\Support;

use Axlon\PostalCodeValidation\Support\Facades\PostalCode;
use Axlon\PostalCodeValidation\Tests\TestCase;

class FacadeTest extends TestCase
{
    public function testItBuildsDependentRules(): void
    {
        $this->assertEquals('postal_code_with:foo', PostalCode::with('foo'));
        $this->assertEquals('postal_code_with:foo,bar', PostalCode::with(['foo', 'bar']));
        $this->assertEquals('postal_code_with:foo,bar,baz', PostalCode::with('foo', 'bar', 'baz'));
    }

    public function testItBuildsExplicitRules(): void
    {
        $this->assertEquals('postal_code:foo', PostalCode::for('foo'));
        $this->assertEquals('postal_code:foo,bar', PostalCode::for(['foo', 'bar']));
        $this->assertEquals('postal_code:foo,bar,baz', PostalCode::for('foo', 'bar', 'baz'));
    }

    public function testItValidatesAgainstAlpha2CountryCodes(): void
    {
        PostalCode::useAlpha2();

        $this->assertTrue(PostalCode::validate('33380', 'FR'));
        $this->assertFalse(PostalCode::validate('33380', 'FRA'));
        $this->assertFalse(PostalCode::validate('33380', 250));
    }

    public function testItValidatesAgainstAlpha3CountryCodes(): void
    {
        PostalCode::useAlpha3();

        $this->assertFalse(PostalCode::validate('33380', 'FR'));
        $this->assertTrue(PostalCode::validate('33380', 'FRA'));
        $this->assertFalse(PostalCode::validate('33380', 250));
    }

    public function testItValidatesAgainstNumericCountryCodes(): void
    {
        PostalCode::useNumeric();

        $this->assertFalse(PostalCode::validate('33380', 'FR'));
        $this->assertFalse(PostalCode::validate('33380', 'FRA'));
        $this->assertTrue(PostalCode::validate('33380', 250));
    }
}
