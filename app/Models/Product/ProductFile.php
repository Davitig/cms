<?php

namespace App\Models\Product;

use App\Concerns\Models\ExtendsQueries;
use App\Concerns\Models\HasFile;
use App\Concerns\Models\QueriesLanguageRelationship;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductFile extends Model
{
    use ExtendsQueries, QueriesLanguageRelationship, HasFile;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'visible', 'position'
    ];

    /**
     * Get the mutated file default attribute.
     *
     * @param  string|null  $value
     * @return string
     */
    public function getFileDefaultAttribute(?string $value): string
    {
        return $value ?: asset('assets/libs/images/image-1.jpg');
    }

    /**
     * Language one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function languages(): HasMany
    {
        return $this->hasMany(ProductFileLanguage::class);
    }

    /**
     * Add a where foreign key clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $foreignKey
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForeignKey(Builder $query, int $foreignKey): Builder
    {
        return $query->where('product_id', $foreignKey);
    }
}
