<?php

declare(strict_types=1);

namespace VOSTPT\Jobs\Api;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;

class ResponseCacheBuster implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    /**
     * Execute the job.
     *
     * @param \Psr\Log\LoggerInterface               $logger
     * @param \Illuminate\Contracts\Cache\Repository $cache
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function handle(LoggerInterface $logger, Cache $cache): bool
    {
        if ($tags = get_cache_bust_tags()) {
            $logger->info(\sprintf('Busting API response cache for: %s', \implode(', ', $tags)));

            if ($cache->tags($tags)->flush()) {
                $logger->info(\sprintf('Clearing bust tags for: %s', \implode(', ', $tags)));

                return clear_cache_bust_tags();
            }
        }

        return false;
    }
}
