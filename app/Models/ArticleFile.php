<?php

namespace App\Models;

use App\Models\Eloquent\Builder;
use App\Models\Eloquent\Model;
use App\Models\Traits\FileTrait;

class ArticleFile extends Model
{
    use FileTrait;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'article_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'article_id', 'position', 'visible'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [
        'article_id'
    ];

    /**
     * Related database table name used by the Language model.
     *
     * @var string
     */
    protected string $languageTable = 'article_file_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected array $languageFillable = [
        'article_file_id', 'language_id', 'title', 'file'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected array $languageNotUpdatable = [
        'article_file_id', 'language_id'
    ];

    /**
     * Get the mutated file default attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getFileDefaultAttribute($value): string
    {
        return $value ?: asset('assets/libs/images/image-1.jpg');
    }

    /**
     * Add a where foreign id clause to the query.
     *
     * @param  int  $foreignId
     * @return \App\Models\Eloquent\Builder|static
     */
    public function byForeign(int $foreignId): Builder|static
    {
        return $this->where('article_id', $foreignId);
    }
}
