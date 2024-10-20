<?php

namespace App\Models;

use App\Models\Abstracts\Model;
use App\Models\Traits\HasGallery;
use App\Models\Traits\LanguageTrait;

class Video extends Model
{
    use HasGallery, LanguageTrait;

    /**
     * Type of the gallery.
     *
     * @var string
     */
    const TYPE = 'videos';

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'videos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gallery_id', 'position', 'visible', 'file'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected $notUpdatable = [
        'gallery_id'
    ];

    /**
     * Related database table name used by the Language model.
     *
     * @var string
     */
    protected $languageTable = 'video_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected $languageFillable = [
        'video_id', 'language_id', 'title'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected $languageNotUpdatable = [
        'video_id', 'language_id'
    ];
}
