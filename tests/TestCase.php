<?php

namespace Antennaio\Clyde\Test;

use Antennaio\Clyde\ClydeServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ClydeServiceProvider::class
        ];
    }
}
