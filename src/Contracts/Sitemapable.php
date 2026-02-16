<?php

namespace Itroteam\Sitemap\Contracts;

use Itroteam\Sitemap\Tags\Url;

interface Sitemapable
{
    public function toSitemapTag(): Url | string | array;
}
