<?php

namespace App\Models\Page;

use App\Models\Base\Builder;
use App\Models\Base\Model;
use App\Models\Traits\LanguageTrait;

class PageLanguage extends Model
{
    use LanguageTrait;

    /**
     * The table associated with the model.
     *
     * @var null|string
     */
    protected $table = 'page_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected $fillable = [
        'page_id', 'language_id', 'title', 'short_title', 'description', 'content', 'meta_title', 'meta_desc'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected array $notUpdatable = [
        'page_id', 'language_id'
    ];

    /**
     * Add a where "page_id" clause to the query.
     *
     * @param  int  $id
     * @return \App\Models\Base\Builder|static
     */
    public function foreignId(int $id): Builder|static
    {
        return $this->where('page_id', $id);
    }
}