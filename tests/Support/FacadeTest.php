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
}
