<?php

namespace App\Models\Page;

use App\Models\Alt\Eloquent\Builder;
use App\Models\Alt\Eloquent\Model;
use App\Models\Alt\Traits\LanguageTrait;

class PageFileLanguage extends Model
{
    use LanguageTrait;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'page_file_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected $fillable = [
        'page_file_id', 'language_id', 'title', 'file'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected array $notUpdatable = [
        'page_file_id', 'language_id'
    ];

    /**
     * Add a where "page_file_id" clause to the query.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  int  $id
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeForeignId(Builder $query, int $id): Builder
    {
        return $query->where('page_file_id', $id);
    }
}
