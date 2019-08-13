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
