<?php

namespace App\Models;

use App\Models\Alt\Base\Builder;
use App\Models\Alt\Base\Model;
use App\Models\Alt\Traits\LanguageTrait;

class PhotoLanguage extends Model
{
    use LanguageTrait;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'photo_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected $fillable = [
        'photo_id', 'language_id', 'title'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected array $notUpdatable = [
        'photo_id', 'language_id'
    ];

    /**
     * Add a where "photo_id" clause to the query.
     *
     * @param  int  $id
     * @return \App\Models\Alt\Base\Builder|static
     */
    public function foreignId(int $id): Builder|static
    {
        return $this->where('photo_id', $id);
    }
}
