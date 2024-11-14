<?php

namespace App\Models;

use App\Models\Alt\Base\Model;
use App\Models\Alt\Contracts\Fileable;
use App\Models\Alt\Traits\HasGallery;
use App\Models\Alt\Traits\HasLanguage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model implements Fileable
{
    use HasGallery, HasLanguage;

    /**
     * Type of the gallery.
     *
     * @var string
     */
    const TYPE = 'videos';

    /**
     * The table associated with the model.
     *
     * @var null|string
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
    protected array $notUpdatable = [
        'gallery_id'
    ];

    /**
     * Set languages a one-to-many relationship.
     *
     * @param  bool  $relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\App\Models\VideoLanguage
     */
    public function languages(bool $relation = true): HasMany|VideoLanguage
    {
        return $relation ? $this->hasMany(VideoLanguage::class) : new VideoLanguage;
    }
}
