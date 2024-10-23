<?php

namespace App\Models\Page;

use App\Models\Base\Builder;
use App\Models\Base\Model;
use App\Models\Traits\LanguageTrait;

class PageFileLanguage extends Model
{
    use LanguageTrait;

    /**
     * The table associated with the model.
     *
     * @var null|string
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
     * @param  int  $id
     * @return \App\Models\Base\Builder|static
     */
    public function foreignId(int $id): Builder|static
    {
        return $this->where('page_file_id', $id);
    }
}
