<?php

namespace App\Models\Gallery;

use App\Models\Base\Builder;
use App\Models\Base\Model;
use App\Models\Traits\LanguageTrait;

class GalleryLanguage extends Model
{
    use LanguageTrait;

    /**
     * The table associated with the model.
     *
     * @var null|string
     */
    protected $table = 'gallery_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected $fillable = [
        'gallery_id', 'language_id', 'title', 'short_title', 'description', 'meta_title', 'meta_desc'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected array $notUpdatable = [
        'gallery_id', 'language_id'
    ];

    /**
     * Add a where "gallery_id" clause to the query.
     *
     * @param  int  $id
     * @return \App\Models\Base\Builder|static
     */
    public function foreignId(int $id): Builder|static
    {
        return $this->where('gallery_id', $id);
    }
}