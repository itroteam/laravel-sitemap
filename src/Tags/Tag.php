<?php

namespace Itroteam\Sitemap\Tags;

abstract class Tag
{
    public function getType(): string
    {
        return mb_strtolower(class_basename(static::class));
    }
}
