<?php

declare(strict_types=1);

namespace VOSTPT\Models\Concerns;

use VOSTPT\Models\Observers\CacheableObserver;

trait Cacheable
{
    /**
     * Cacheable boot logic.
     *
     * @return void
     */
    public static function bootCacheable(): void
    {
        static::observe(new CacheableObserver());
    }
}
