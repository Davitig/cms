<?php

namespace App\Models\Page;

use App\Models\Alt\Eloquent\Builder;
use App\Models\Alt\Eloquent\Model;
use App\Models\Alt\Traits\FileableTrait;
use App\Models\Alt\Traits\HasLanguage;
use App\Models\Alt\Traits\HasSubModels;
use App\Models\Alt\Traits\PositionableTrait;
use App\Models\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use HasLanguage, PositionableTrait, FileableTrait, HasSubModels;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'menu_id', 'slug', 'position', 'visible', 'parent_id', 'type', 'type_id', 'template', 'collapse', 'image'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [];

    /**
     * Set languages a one-to-many relationship.
     *
     * @param  bool  $relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\App\Models\Page\PageLanguage
     */
    public function languages(bool $relation = true): HasMany|PageLanguage
    {
        return $relation ? $this->hasMany(PageLanguage::class) : new PageLanguage;
    }

    /**
     * Build an admin query.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  int|null  $menuId
     * @param  mixed  $currentLang
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeForAdmin(
        Builder $query, ?int $menuId = null, mixed $currentLang = true
    ): Builder
    {
        return $query->when(! is_null($menuId), function ($q) use ($menuId) {
            return $q->menuId($menuId);
        })->joinLanguage($currentLang)
            ->joinCollection()
            ->filesExists()
            ->positionAsc();
    }

    /**
     * Build a public query.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  mixed  $currentLang
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeForPublic(Builder $query, mixed $currentLang = true): Builder
    {
        return $query->joinLanguage($currentLang)->whereVisible();
    }

    /**
     * Add a query, which is valid for routing.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  string  $slug
     * @param  int  $parentId
     * @param  mixed  $currentLang
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeBySlugRoute(
        Builder $query, string $slug, int $parentId, mixed $currentLang = true
    ): Builder
    {
        return $query->parentId($parentId)->where('slug', $slug)->forPublic($currentLang);
    }

    /**
     * Add a where "menu_id" clause to the query.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  int  $id
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeMenuId(Builder $query, int $id): Builder
    {
        return $query->where('menu_id', $id);
    }

    /**
     * Add a where "type" clause to the query.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  string  $value
     * @param  string  $operator
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeTypeName(Builder $query, string $value, string $operator = '='): Builder
    {
        return $query->where('type', $operator, $value);
    }

    /**
     * Add a where "type_id" clause to the query.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  int  $id
     * @param  string  $operator
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeTypeId(Builder $query, int $id, string $operator = '='): Builder
    {
        return $query->where('type_id', $operator, $id);
    }

    /**
     * Add a where "visible" clause to the query.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  int  $value
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeWhereVisible(Builder $query, int $value = 1): Builder
    {
        return $query->where('visible', $value);
    }

    /**
     * Add a "collection" join to the query.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeJoinCollection(Builder $query): Builder
    {
        $table = (new Collection)->getTable();

        $columns = [
            $table . '.title as collection_title',
            $table . '.type as collection_type',
        ];

        return $query->leftJoin($table, function ($q) use ($table) {
            return $q->where($this->getTable() . '.type', 'collections')
                ->on('type_id', $table . '.id');
        })->addSelect($columns);
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $attributes = [])
    {
        if (empty($attributes['position'])) {
            if (isset($attributes['menu_id'])) {
                $attributes['position'] = $this->menuId($attributes['menu_id'])
                        ->max('position') + 1;
            } else {
                $attributes['position'] = $this->max('position') + 1;
            }
        }

        return parent::create($attributes);
    }
}
