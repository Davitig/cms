<?php

namespace App\Models;

use App\Models\Abstracts\Model;
use App\Models\Traits\LanguageTrait;
use App\Models\Traits\PositionableTrait;

class Slider extends Model
{
    use LanguageTrait, PositionableTrait;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'slider';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'position', 'visible', 'link', 'file'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected $notUpdatable = [];

    /**
     * Related database table name used by the Language model.
     *
     * @var string
     */
    protected $languageTable = 'slider_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected $languageFillable = [
        'slider_id', 'language_id', 'title', 'description'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected $languageNotUpdatable = [
        'slider_id', 'language_id'
    ];

    /**
     * Get the mutated file attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getFileDefaultAttribute($value)
    {
        return $value ?: asset('assets/libs/images/image-1.jpg');
    }

    /**
     * Build a query for admin.
     *
     * @param  mixed  $currentLang
     * @param  array  $columns
     * @return \App\Models\Builder\Builder
     */
    public function forAdmin($currentLang = true, array $columns = [])
    {
        return $this->joinLanguage($currentLang, $columns)->positionDesc();
    }

    /**
     * Build a public query.
     *
     * @param  mixed  $currentLang
     * @param  array  $columns
     * @return \App\Models\Builder\Builder
     */
    public function forPublic($currentLang = true, array $columns = [])
    {
        return $this->joinLanguage($currentLang, $columns)
            ->where('visible', 1)
            ->whereNotNull('file')
            ->where('file', '!=', '')
            ->positionDesc();
    }
}
