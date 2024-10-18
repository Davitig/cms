<?php

namespace Models;

use Models\Abstracts\Model;
use Models\Traits\LanguageTrait;
use Models\Traits\HasGallery;

class Photo extends Model
{
    use HasGallery, LanguageTrait;

    /**
     * Type of the gallery.
     *
     * @var string
     */
    const TYPE = 'photos';

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'photos';

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
    protected $languageTable = 'photo_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected $languageFillable = [
        'photo_id', 'language_id', 'title'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected $languageNotUpdatable = [
        'photo_id', 'language_id'
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
}
