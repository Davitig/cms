<?php

namespace App\Models;

use App\Models\Alt\Eloquent\Builder;
use App\Models\Alt\Eloquent\Model;
use App\Models\Alt\Traits\HasLanguage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Translation extends Model
{
    use HasLanguage;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'translations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'title', 'type'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [
        'code'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected array $languageNotUpdatable = [
        'translation_id', 'language_id'
    ];

    /**
     * Set languages a one-to-many relationship.
     *
     * @param  bool  $relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\App\Models\TranslationLanguage
     */
    public function languages(bool $relation = true): HasMany|TranslationLanguage
    {
        return $relation ? $this->hasMany(TranslationLanguage::class) : new TranslationLanguage;
    }

    /**
     * Build a query by code.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  string  $code
     * @param  mixed  $currentLang
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeByCode(Builder $query, string $code, mixed $currentLang = true): Builder
    {
        return $query->joinLanguage($currentLang)->where('code', $code);
    }
}
