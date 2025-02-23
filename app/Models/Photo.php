<?php

namespace App\Models;

use App\Models\Alt\Contracts\Fileable;
use App\Models\Alt\Traits\HasGallery;
use App\Models\Alt\Traits\HasLanguage;
use App\Models\Alt\Traits\QueriesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Photo extends Model implements Fileable
{
    use QueriesTrait, HasGallery, HasLanguage;

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
     * Get the mutated file attribute.
     *
     * @param  string|null  $value
     * @return string
     */
    public function getFileDefaultAttribute(?string $value): string
    {
        return $value ?: asset('assets/libs/images/image-1.jpg');
    }

    /**
     * Set languages a one-to-many relationship.
     *
     * @param  bool  $relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\App\Models\PhotoLanguage
     */
    public function languages(bool $relation = true): HasMany|PhotoLanguage
    {
        return $relation ? $this->hasMany(PhotoLanguage::class) : new PhotoLanguage;
    }
}
