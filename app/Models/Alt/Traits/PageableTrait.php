<?php

namespace App\Models\Alt\Traits;

use App\Models\Page\Page;
use Illuminate\Database\Eloquent\Builder;

trait PageableTrait
{
    /**
     * Add a "pages" join to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @param  string  $foreignKey
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeJoinPage(
        Builder $query, string $type = 'right', string $foreignKey = 'collection_id'
    ): Builder
    {
        return $query->join('pages', $foreignKey, 'type_id', $type)
            ->leftJoin('page_languages', function ($q) {
                return $q->on('page_languages.page_id', 'pages.id')
                    ->where(function ($q) {
                        return $q->where('page_languages.language', language())
                            ->orWhereNull('page_languages.language');
                    });
            })->where('pages.visible', 1)->addSelect([
                'pages.parent_id',
                'pages.slug as parent_slug',
                'page_languages.title as parent_title'
            ]);
    }

    /**
     * Get model full slug.
     *
     * @param  int|null  $value
     * @param  string|null  $column
     * @return string|null
     */
    public function getFullSlug(?int $value = null, ?string $column = null): ?string
    {
        return $this->fullSlug($value, $column)->full_slug;
    }

    /**
     * Set model full slug.
     *
     * @param  int|null  $value
     * @param  string|null  $column
     * @return $this
     */
    public function fullSlug(?int $value = null, ?string $column = null): static
    {
        $this->full_slug ??= $this->slug;

        if (is_null($column)) {
            $column = is_null($value) && $this->collection_id
                ? 'type_id'
                : $this->getKeyName();
        }

        if (! ($value = (is_null($value) ? $this->collection_id : $value))) {
            return $this;
        }

        $model = (new Page)->where($column, $value)->first(['slug', 'parent_id']);

        if (is_null($model)) {
            return $this;
        }

        $this->full_slug = trim($model->getFullSlug() . '/' . $this->full_slug,'/');

        return $this;
    }
}
