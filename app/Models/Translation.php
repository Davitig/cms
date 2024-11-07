<?php

namespace App\Models;

use App\Models\Base\Builder;
use App\Models\Base\Model;
use App\Models\Traits\HasLanguage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Translation extends Model
{
    use HasLanguage;

    /**
     * The table associated with the model.
     *
     * @var null|string
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
     * @param  string  $code
     * @param  mixed  $currentLang
     * @return \App\Models\Base\Builder
     */
    public function byCode(string $code, mixed $currentLang = true): Builder
    {
        return $this->joinLanguage($currentLang)->where('code', $code);
    }
}
