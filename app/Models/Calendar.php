<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'calendar';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cms_user_id', 'title', 'description', 'color', 'start', 'end'
    ];

    /**
     * The list of the available colors.
     *
     * @var array
     */
    protected array $colors = [
        'red', 'blue', 'green', 'orange', 'turquoise', 'purple', 'black', 'gray'
    ];

    /**
     * Get a random color.
     *
     * @return string
     */
    public function getRandomColor(): string
    {
        return $this->colors[rand(0, count($this->colors) - 1)];
    }

    /**
     * Add a where 'cms_user_id' clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUserId(Builder $query, int $userId): Builder
    {
        return $query->where('cms_user_id', $userId);
    }

    /**
     * Build a query based on active dates.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|null  $start
     * @param  string|null  $end
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query, ?string $start = null, ?string $end = null): Builder
    {
        if (is_null($start)) {
            $start = date('Y-m') . '-01';

            $start = date('Y-m-d', strtotime('-10 days', strtotime($start)));
        }

        if (is_null($end)) {
            $end = date('Y-m-d', strtotime('+50 days', strtotime($start)));
        }

        return $query->whereNotNull('start')->whereBetween('start', [$start, $end]);
    }

    /**
     * Build a query based on inactive dates.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->whereNull('start');
    }
}
