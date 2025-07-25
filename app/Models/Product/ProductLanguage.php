<?php

namespace App\Models\Product;

use App\Concerns\Models\QueriesWithLanguage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProductLanguage extends Model
{
    use QueriesWithLanguage;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'language_id', 'title', 'description', 'content', 'meta_title', 'meta_desc'
    ];

    /**
     * Add a where "product_id" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $foreignKey
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLanguageForeignKey(Builder $query, int $foreignKey): Builder
    {
        return $query->where('product_id', $foreignKey);
    }
}
