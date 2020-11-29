<?php

namespace Axlon\PostalCodeValidation\Tests;

use Axlon\PostalCodeValidation\ValidationServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Get the package service providers.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [
            ValidationServiceProvider::class,
        ];
    }
}
