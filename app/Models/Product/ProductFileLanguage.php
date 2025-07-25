<?php

namespace App\Models\Product;

use App\Concerns\Models\QueriesWithLanguage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProductFileLanguage extends Model
{
    use QueriesWithLanguage;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_file_id', 'language_id', 'title', 'file'
    ];

    /**
     * Add a where "product_file_id" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $foreignKey
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLanguageForeignKey(Builder $query, int $foreignKey): Builder
    {
        return $query->where('product_file_id', $foreignKey);
    }
}
