<?php

namespace App\Models;

use App\Concerns\Models\ExtendsQueries;
use App\Concerns\Models\QueriesLanguageRelationship;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Translation extends Model
{
    use ExtendsQueries, QueriesLanguageRelationship;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'type'
    ];

    /**
     * Languages' one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function languages(): HasMany
    {
        return $this->hasMany(TranslationLanguage::class);
    }

    /**
     * Build a query by code.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $code
     * @param  mixed  $currentLang
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCode(Builder $query, string $code, mixed $currentLang = true): Builder
    {
        return $query->joinLanguage($currentLang)->where('code', $code);
    }
}
