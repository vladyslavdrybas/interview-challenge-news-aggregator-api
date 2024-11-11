<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function apiRoutePrefix(): string
    {
        return 'api/v' . config('app.api.version');
    }

    protected function apiRoute(string $route): string
    {
        return $this->apiRoutePrefix() . $route;
    }
}
