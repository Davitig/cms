<?php

namespace App\Models;

use App\Models\Alt\Traits\ModelBuilderTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use ModelBuilderTrait;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'collections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'title', 'type', 'admin_order_by', 'admin_sort', 'admin_per_page',
        'web_order_by', 'web_sort', 'web_per_page', 'description'
    ];

    /**
     * Build a public dynamic route query.
     *
     * @param  int  $id
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function publicDynamicRoute(int $id, string $type): Builder
    {
        return (new static)->whereKey($id)->byType($type)->addQualifiedSelect('*');
    }

    /**
     * Add a new where 'type' clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }
}
