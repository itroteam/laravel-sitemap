<?php

namespace Itroteam\Sitemap\Test;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Itroteam\Sitemap\SitemapServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SitemapServiceProvider::class,
        ];
    }
}
