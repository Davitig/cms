<?php

namespace App\Models;

use App\Concerns\Models\ExtendsQueries;
use App\Models\Article\Article;
use App\Models\Event\Event;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collection extends Model
{
    use ExtendsQueries;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'type', 'admin_order_by', 'admin_sort', 'admin_per_page', 'admin_max_similar_type',
        'web_order_by', 'web_sort', 'web_per_page', 'description'
    ];

    /**
     * Article one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Event one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Build a public dynamic route query.
     *
     * @param  int  $id
     * @param  mixed  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function publicDynamicRoute(int $id, mixed $type): Builder
    {
        return (new static)->whereKey($id)->byType($type)->addQualifiedSelect('*');
    }

    /**
     * Add a new where 'type' clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByType(Builder $query, mixed $type): Builder
    {
        return $query->where('type', $type);
    }
}
