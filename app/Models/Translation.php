<?php

namespace App\Models;

use App\Models\Eloquent\Builder;
use App\Models\Eloquent\Model;
use App\Models\Traits\LanguageTrait;

class Translation extends Model
{
    use LanguageTrait;

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
     * Related database table name used by the Language model.
     *
     * @var string
     */
    protected string $languageTable = 'translation_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected array $languageFillable = [
        'translation_id', 'language_id', 'value'
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
     * Build a query by code.
     *
     * @param  string  $code
     * @param  bool|string  $currentLang
     * @return \App\Models\Eloquent\Builder
     */
    public function byCode(string $code, bool|string $currentLang = true): Builder
    {
        return $this->joinLanguage($currentLang)->where('code', $code);
    }
}
