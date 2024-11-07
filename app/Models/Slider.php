<?php

namespace App\Models;

use App\Models\Base\Builder;
use App\Models\Base\Model;
use App\Models\Traits\HasLanguage;
use App\Models\Traits\PositionableTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Slider extends Model
{
    use HasLanguage, PositionableTrait;

    /**
     * The table associated with the model.
     *
     * @var null|string
     */
    protected $table = 'slider';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'position', 'visible', 'link', 'file'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [];

    /**
     * Get the mutated file attribute.
     *
     * @param  string|null  $value
     * @return string
     */
    public function getFileDefaultAttribute(?string $value): string
    {
        return $value ?: asset('assets/libs/images/image-1.jpg');
    }

    /**
     * Set languages a one-to-many relationship.
     *
     * @param  bool  $relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\App\Models\SliderLanguage
     */
    public function languages(bool $relation = true): HasMany|SliderLanguage
    {
        return $relation ? $this->hasMany(SliderLanguage::class) : new SliderLanguage;
    }

    /**
     * Build a query for admin.
     *
     * @param  mixed  $currentLang
     * @param  array|string  $columns
     * @return \App\Models\Base\Builder
     */
    public function forAdmin(mixed $currentLang = true, array|string $columns = []): Builder
    {
        return $this->joinLanguage($currentLang, $columns)->positionDesc();
    }

    /**
     * Build a public query.
     *
     * @param  mixed  $currentLang
     * @param  array|string  $columns
     * @return \App\Models\Base\Builder
     */
    public function forPublic(mixed $currentLang = true, array|string $columns = []): Builder
    {
        return $this->joinLanguage($currentLang, $columns)
            ->where('visible', 1)
            ->whereNotNull('file')
            ->where('file', '!=', '')
            ->positionDesc();
    }
}
