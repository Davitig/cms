<?php

namespace App\Models;

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
    protected $notUpdatable = [
        'code'
    ];

    /**
     * Related database table name used by the Language model.
     *
     * @var string
     */
    protected $languageTable = 'translation_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected $languageFillable = [
        'translation_id', 'language_id', 'value'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected $languageNotUpdatable = [
        'translation_id', 'language_id'
    ];

    /**
     * Build a query by code.
     *
     * @param  string  $code
     * @param  mixed  $currentLang
     * @return \App\Models\Eloquent\Builder
     */
    public function byCode($code, $currentLang = true)
    {
        return $this->joinLanguage($currentLang)->where('code', $code);
    }
}
