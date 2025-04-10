<?php

namespace App\Models;

use App\Models\Alt\Traits\LanguageTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FaqLanguage extends Model
{
    use LanguageTrait;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'faq_languages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'faq_id', 'language_id', 'title', 'description', 'meta_title', 'meta_desc'
    ];

    /**
     * Add a where "faq_id" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $foreignKey
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLanguageForeignKey(Builder $query, int $foreignKey): Builder
    {
        return $query->where('faq_id', $foreignKey);
    }
}
