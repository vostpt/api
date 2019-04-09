<?php

declare(strict_types=1);

namespace VOSTPT\Filters;

use Illuminate\Database\Eloquent\Builder;
use OutOfBoundsException;

abstract class Filter implements Contracts\Filter
{
    /**
     * Sort column.
     *
     * @var string
     */
    protected $sortColumn = 'created_at';

    /**
     * Sort order.
     *
     * @var string
     */
    protected $sortOrder = 'desc';

    /**
     * Page number.
     *
     * @var int
     */
    protected $pageNumber = 1;

    /**
     * Number of items per page.
     *
     * @var int
     */
    protected $itemsPerPage = 100;

    /**
     * Text search for filtering.
     *
     * @var array
     */
    protected $search = [];

    /**
     * Relations to eager load.
     *
     * @var array
     */
    protected $relations = [];

    /**
     * {@inheritDoc}
     */
    public static function getOrderValues(): array
    {
        return [
            'asc',
            'desc',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getQualifiedSortColumn(): string
    {
        return \sprintf('%s.%s', $this->getTable(), $this->sortColumn);
    }

    /**
     * {@inheritDoc}
     */
    public function getSortColumn(): string
    {
        return $this->sortColumn;
    }

    /**
     * {@inheritDoc}
     */
    public function setSortColumn(string $column): Contracts\Filter
    {
        if (! \in_array($column, static::getSortableColumns(), true)) {
            throw new OutOfBoundsException(\sprintf(
                'The sort column must be one of: %s',
                \implode(', ', static::getSortableColumns())
            ));
        }

        $this->sortColumn = $column;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSortOrder(): string
    {
        return $this->sortOrder;
    }

    /**
     * {@inheritDoc}
     */
    public function setSortOrder(string $order): Contracts\Filter
    {
        if (! \in_array(\mb_strtolower($order), static::getOrderValues(), true)) {
            throw new OutOfBoundsException(\sprintf(
                'The sort order must be one of: %s',
                \implode(', ', static::getOrderValues())
            ));
        }

        $this->sortOrder = $order;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    final public function getPageNumber(): int
    {
        return $this->pageNumber;
    }

    /**
     * {@inheritDoc}
     */
    final public function setPageNumber(int $page): Contracts\Filter
    {
        if ($page < 1) {
            throw new OutOfBoundsException('The page number must be equal or greater than one');
        }

        $this->pageNumber = $page;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    final public function getPageSize(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * {@inheritDoc}
     */
    final public function setPageSize(int $items): Contracts\Filter
    {
        if ($items < 1) {
            throw new OutOfBoundsException('The page size number must be equal or greater than one');
        }

        $this->itemsPerPage = $items;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withSearch(string $text): Contracts\Filter
    {
        // Normalise search patterns
        foreach (\preg_split('/[\s\|,;]+/', $text, 0, PREG_SPLIT_NO_EMPTY) as $word) {
            $this->search[] = \mb_strtolower(\str_replace('%', '\%', $word));
        }

        return $this;
    }

    /**
     * Apply default text search filtering.
     *
     * @param Builder $builder
     *
     * @return void
     */
    protected function applySearch(Builder $builder): void
    {
        if ($this->search) {
            $builder->where(function (Builder $query) {
                foreach (static::getSearchableColumns() as $column) {
                    foreach ($this->search as $pattern) {
                        $query->orWhere($column, 'LIKE', "%$pattern%");
                    }
                }
            });
        }
    }

    /**
     * {@inheritDoc}
     */
    public function withRelations(array $relations): Contracts\Filter
    {
        $this->relations = $relations;

        return $this;
    }

    /**
     * Apply eager loading of relations.
     *
     * @param Builder $builder
     *
     * @return void
     */
    protected function applyRelations(Builder $builder): void
    {
        if ($this->relations) {
            $builder->with($this->relations);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Builder $builder): void
    {
        // Specify which columns to retrieve
        $builder->addSelect($this->getColumns());

        // Apply relation eager loading
        $this->applyRelations($builder);

        // Apply text search filtering
        $this->applySearch($builder);

        // Apply sorting
        $builder->orderBy($this->getQualifiedSortColumn(), $this->sortOrder);
    }

    /**
     * {@inheritDoc}
     */
    public function getSignatureElements(): array
    {
        return [
            $this->sortColumn,
            $this->sortOrder,
            $this->pageNumber,
            $this->itemsPerPage,
            \implode(',', $this->search),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getSignature(bool $hash = false): string
    {
        $signature = \sprintf('%s::%s', class_basename($this), \implode('|', $this->getSignatureElements()));

        return $hash ? \sha1($signature) : $signature;
    }
}
