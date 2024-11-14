<?php

namespace App\Models;

use App\Models\Alt\Base\Builder;
use App\Models\Alt\Base\Model;
use App\Models\Alt\Traits\LanguageTrait;

class TranslationLanguage extends Model
{
    use LanguageTrait;

    /**
     * The table associated with the model.
     *
     * @var null|string
     */
    protected $table = 'translation_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected $fillable = [
        'translation_id', 'language_id', 'value'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected array $notUpdatable = [
        'translation_id', 'language_id'
    ];

    /**
     * Add a where "translation_id" clause to the query.
     *
     * @param  int  $id
     * @return \App\Models\Alt\Base\Builder|static
     */
    public function foreignId(int $id): Builder|static
    {
        return $this->where('translation_id', $id);
    }
}
