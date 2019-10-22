<?php

declare(strict_types=1);

if (! \function_exists('joined')) {

    /**
     * Check if a Builder has a joined table.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $table
     *
     * @return bool
     */
    function joined(Illuminate\Database\Eloquent\Builder $builder, string $table): bool
    {
        foreach ((array) $builder->getQuery()->joins as $join) {
            if ($join->table === $table) {
                return true;
            }
        }

        return false;
    }
}

if (! \function_exists('get_cache_bust_tags')) {

    /**
     * Get the current cache bust tags.
     *
     * @throws Exception
     *
     * @return array
     */
    function get_cache_bust_tags(): array
    {
        return cache('tags_for_cache_busting', []);
    }
}

if (! \function_exists('add_cache_bust_tag')) {

    /**
     * Add a cache bust tag.
     *
     * @param string $tag
     *
     * @throws Exception
     *
     * @return bool
     */
    function add_cache_bust_tag(string $tag): bool
    {
        $tags = \array_unique(get_cache_bust_tags());

        if (\in_array($tag, $tags, true)) {
            return true;
        }

        return cache()->forever('tags_for_cache_busting', \array_merge($tags, [
            $tag,
        ]));
    }
}

if (! \function_exists('clear_cache_bust_tags')) {

    /**
     * Clear the cache bust tags.
     *
     * @throws Exception
     *
     * @return bool
     */
    function clear_cache_bust_tags(): bool
    {
        return cache()->forget('tags_for_cache_busting');
    }
}
