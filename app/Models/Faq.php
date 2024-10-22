<?php

namespace App\Models;

use App\Models\Eloquent\Model;
use App\Models\Traits\HasCollection;
use App\Models\Traits\LanguageTrait;

class Faq extends Model
{
    use HasCollection, LanguageTrait;

    /**
     * Type of the collection.
     *
     * @var string
     */
    const TYPE = 'faq';

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'faq';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'collection_id', 'position', 'visible'
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
    protected $languageTable = 'faq_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected $languageFillable = [
        'faq_id', 'language_id', 'title', 'description'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected $languageNotUpdatable = [
        'faq_id', 'language_id'
    ];
}
