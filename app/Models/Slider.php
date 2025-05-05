<?php

namespace App\Models;

use App\Models\Alt\Contracts\Fileable;
use App\Models\Alt\Traits\HasLanguage;
use App\Models\Alt\Traits\PositionableTrait;
use App\Models\Alt\Traits\QueriesTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Slider extends Model implements Fileable
{
    use QueriesTrait, HasLanguage, PositionableTrait;

    /**
     * The table associated with the model.
     *
     * @var string|null
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
     * Languages' one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function languages(): HasMany
    {
        return $this->hasMany(SliderLanguage::class);
    }

    /**
     * Build a query for admin.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $currentLang
     * @param  array|string  $columns
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForAdmin(
        Builder $query, mixed $currentLang = true, array|string $columns = []
    ): Builder
    {
        return $query->joinLanguage($currentLang, $columns)->positionDesc();
    }

    /**
     * Build a public query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $currentLang
     * @param  array|string  $columns
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForPublic(
        Builder $query, mixed $currentLang = true, array|string $columns = []
    ): Builder
    {
        return $query->joinLanguage($currentLang, $columns)
            ->where($this->qualifyColumn('visible'), 1)
            ->whereNotNull('file')
            ->where('file', '!=', '')
            ->positionDesc();
    }
}
