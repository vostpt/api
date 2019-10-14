<?php

declare(strict_types=1);

namespace VOSTPT\Models\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheableObserver
{
    /**
     * Handle the created event.
     *
     * @param Model $model
     *
     * @return void
     */
    public function created(Model $model): void
    {
        $this->flushCache($model);
    }

    /**
     * Handle the updated event.
     *
     * @param Model $model
     *
     * @return void
     */
    public function updated(Model $model): void
    {
        $this->flushCache($model);
    }

    /**
     * Handle the deleted event.
     *
     * @param Model $model
     *
     * @return void
     */
    public function deleted(Model $model): void
    {
        $this->flushCache($model);
    }

    /**
     * Flush the cache of a model class.
     *
     * @param Model $model
     *
     * @return void
     */
    private function flushCache(Model $model): void
    {
        $tag = \get_class($model);

        Log::info(\sprintf('Flushing "%s" cache', $tag));

        Cache::tags($tag)->flush();
    }
}
