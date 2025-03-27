<?php

namespace App\Models\Page;

use App\Models\Alt\Traits\FileableTrait;
use App\Models\Alt\Traits\HasLanguage;
use App\Models\Alt\Traits\HasSubModels;
use App\Models\Alt\Traits\PositionableTrait;
use App\Models\Alt\Traits\ModelBuilderTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use ModelBuilderTrait, HasLanguage, PositionableTrait, FileableTrait, HasSubModels;

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
        'menu_id', 'slug', 'position', 'visible', 'parent_id', 'type', 'type_id', 'collapse', 'image'
    ];

    /**
     * Set languages a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function languages(): HasMany
    {
        return $this->hasMany(PageLanguage::class);
    }

    /**
     * Build a public dynamic route query.
     *
     * @param  string  $slug
     * @param  int  $parentId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function publicDynamicRoute(string $slug, int $parentId): Builder
    {
        return (new static)->bySlugRoute($slug, $parentId)
            ->addQualifiedSelect('id', 'slug', 'type');
    }

    /**
     * Build an admin query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int|null  $menuId
     * @param  mixed  $currentLang
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForAdmin(
        Builder $query, ?int $menuId = null, mixed $currentLang = true
    ): Builder
    {
        return $query->when(! is_null($menuId), function ($q) use ($menuId) {
            return $q->menuId($menuId);
        })->joinLanguage($currentLang)->filesExists()->positionAsc();
    }

    /**
     * Build a public query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $currentLang
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForPublic(Builder $query, mixed $currentLang = true): Builder
    {
        return $query->joinLanguage($currentLang)->whereVisible();
    }

    /**
     * Add a query, which is valid for routing.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $slug
     * @param  int  $parentId
     * @param  mixed  $currentLang
     * @return \Illuminate\Database\Eloquent\Builder
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
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMenuId(Builder $query, int $id): Builder
    {
        return $query->where('menu_id', $id);
    }

    /**
     * Add a where "type" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $value
     * @param  string  $operator
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTypeName(Builder $query, string $value, string $operator = '='): Builder
    {
        return $query->where('type', $operator, $value);
    }

    /**
     * Add a where "type_id" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $id
     * @param  string  $operator
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTypeId(Builder $query, int $id, string $operator = '='): Builder
    {
        return $query->where('type_id', $operator, $id);
    }

    /**
     * Add a where "visible" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereVisible(Builder $query, int $value = 1): Builder
    {
        return $query->where($this->qualifyColumn('visible'), $value);
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
