<?php

namespace App\Models;

use App\Models\Base\Builder;
use App\Models\Base\Model;
use App\Models\Traits\LanguageTrait;

class SliderLanguage extends Model
{
    use LanguageTrait;

    /**
     * The table associated with the model.
     *
     * @var null|string
     */
    protected $table = 'slider_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected $fillable = [
        'slider_id', 'language_id', 'title', 'description'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected array $notUpdatable = [
        'slider_id', 'language_id'
    ];

    /**
     * Add a where "slider_id" clause to the query.
     *
     * @param  int  $id
     * @return \App\Models\Base\Builder|static
     */
    public function foreignId(int $id): Builder|static
    {
        return $this->where('slider_id', $id);
    }
}
