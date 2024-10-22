<?php

namespace App\Models;

use App\Models\Eloquent\Builder;
use App\Models\Eloquent\Model;
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
    protected array $notUpdatable = [];

    /**
     * Related database table name used by the Language model.
     *
     * @var string
     */
    protected string $languageTable = 'slider_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected array $languageFillable = [
        'slider_id', 'language_id', 'title', 'description'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected array $languageNotUpdatable = [
        'slider_id', 'language_id'
    ];

    /**
     * Get the mutated file attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getFileDefaultAttribute($value): string
    {
        return $value ?: asset('assets/libs/images/image-1.jpg');
    }

    /**
     * Build a query for admin.
     *
     * @param  bool|string  $currentLang
     * @param  array|string  $columns
     * @return \App\Models\Eloquent\Builder
     */
    public function forAdmin(bool|string $currentLang = true, array|string $columns = []): Builder
    {
        return $this->joinLanguage($currentLang, $columns)->positionDesc();
    }

    /**
     * Build a public query.
     *
     * @param  bool|string  $currentLang
     * @param  array|string  $columns
     * @return \App\Models\Eloquent\Builder
     */
    public function forPublic(bool|string $currentLang = true, array|string $columns = []): Builder
    {
        return $this->joinLanguage($currentLang, $columns)
            ->where('visible', 1)
            ->whereNotNull('file')
            ->where('file', '!=', '')
            ->positionDesc();
    }
}
