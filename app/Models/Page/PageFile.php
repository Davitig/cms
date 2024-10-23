<?php

namespace App\Models\Page;

use App\Models\Base\Builder;
use App\Models\Base\Model;
use App\Models\Traits\FileTrait;
use App\Models\Traits\HasLanguage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageFile extends Model
{
    use HasLanguage, FileTrait;

    /**
     * The table associated with the model.
     *
     * @var null|string
     */
    protected $table = 'page_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_id', 'position', 'visible'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [
        'page_id'
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
     * Set languages a one-to-many relationship.
     *
     * @param  bool  $relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\App\Models\Page\PageFileLanguage
     */
    public function languages(bool $relation = true): HasMany|PageFileLanguage
    {
        return $relation ? $this->hasMany(PageFileLanguage::class) : new PageFileLanguage;
    }

    /**
     * Add a where foreign id clause to the query.
     *
     * @param  int  $foreignId
     * @return \App\Models\Base\Builder|static
     */
    public function byForeign(int $foreignId): Builder|static
    {
        return $this->where('page_id', $foreignId);
    }
}
