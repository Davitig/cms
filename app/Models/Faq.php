<?php

namespace App\Models;

use App\Models\Alt\Base\Model;
use App\Models\Alt\Traits\HasCollection;
use App\Models\Alt\Traits\HasLanguage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faq extends Model
{
    use HasCollection, HasLanguage;

    /**
     * Type of the collection.
     *
     * @var string
     */
    const TYPE = 'faq';

    /**
     * The table associated with the model.
     *
     * @var null|string
     */
    protected $table = 'faq';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'collection_id', 'position', 'visible'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [];

    /**
     * Set languages a one-to-many relationship.
     *
     * @param  bool  $relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\App\Models\FaqLanguage
     */
    public function languages(bool $relation = true): HasMany|FaqLanguage
    {
        return $relation ? $this->hasMany(FaqLanguage::class) : new FaqLanguage;
    }
}
