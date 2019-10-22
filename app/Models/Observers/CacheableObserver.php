<?php

declare(strict_types=1);

namespace VOSTPT\Models\Observers;

use Illuminate\Database\Eloquent\Model;

class CacheableObserver
{
    /**
     * Handle the created event.
     *
     * @param Model $model
     *
     * @throws \Exception
     *
     * @return void
     */
    public function created(Model $model): void
    {
        $this->markForCacheBust($model);
    }

    /**
     * Handle the updated event.
     *
     * @param Model $model
     *
     * @throws \Exception
     *
     * @return void
     */
    public function updated(Model $model): void
    {
        $this->markForCacheBust($model);
    }

    /**
     * Handle the deleted event.
     *
     * @param Model $model
     *
     * @throws \Exception
     *
     * @return void
     */
    public function deleted(Model $model): void
    {
        $this->markForCacheBust($model);
    }

    /**
     * Mark cache for bust.
     *
     * @param Model $model
     *
     * @throws \Exception
     *
     * @return void
     */
    private function markForCacheBust(Model $model): void
    {
        add_cache_bust_tag(\get_class($model));
    }
}
