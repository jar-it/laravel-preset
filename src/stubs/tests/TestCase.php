<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        TestResponse::macro('data', function ($key) {
            return $this->original->getData()[$key];
        });
    }
}
