<?php

namespace App\Models;

use App\Models\Alt\Base\Builder;
use App\Models\Alt\Base\Model;
use App\Models\Alt\Traits\LanguageTrait;

class FaqLanguage extends Model
{
    use LanguageTrait;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'faq_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected $fillable = [
        'faq_id', 'language_id', 'title', 'short_title', 'description', 'meta_title', 'meta_desc'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected array $notUpdatable = [
        'faq_id', 'language_id'
    ];

    /**
     * Add a where "faq_id" clause to the query.
     *
     * @param  int  $id
     * @return \App\Models\Alt\Base\Builder|static
     */
    public function foreignId(int $id): Builder|static
    {
        return $this->where('faq_id', $id);
    }
}
