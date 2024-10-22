<?php

namespace App\Models;

use App\Models\Eloquent\Model;
use App\Models\Traits\HasGallery;
use App\Models\Traits\LanguageTrait;

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
    protected array $notUpdatable = [
        'gallery_id'
    ];

    /**
     * Related database table name used by the Language model.
     *
     * @var string
     */
    protected string $languageTable = 'photo_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected array $languageFillable = [
        'photo_id', 'language_id', 'title'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected array $languageNotUpdatable = [
        'photo_id', 'language_id'
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
}
