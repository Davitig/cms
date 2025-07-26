<?php

namespace App\Models\Product;

use App\Concerns\Models\ExtendsQueries;
use App\Concerns\Models\Fileable;
use App\Concerns\Models\Positionable;
use App\Concerns\Models\QueriesLanguageRelationship;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use ExtendsQueries, QueriesLanguageRelationship, Positionable, Fileable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug', 'visible', 'position', 'in_stock', 'price', 'quantity', 'image'
    ];

    /**
     * Language one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function languages(): HasMany
    {
        return $this->hasMany(ProductLanguage::class);
    }

    /**
     * Build a query based on the slug.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $slug
     * @param  int|null  $collectionId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug)->forPublic();
    }

    /**
     * Build an admin query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $currentLang
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForAdmin(Builder $query, mixed $currentLang = true): Builder
    {
        return $query->joinLanguage($currentLang)->filesExists()->createdDesc();
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
        return $query->joinLanguage($currentLang, [], 'right')->whereVisible()->createdDesc();
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
}
