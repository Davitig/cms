<?php

namespace App\Models;

use App\Models\Base\Builder;
use App\Models\Base\Model;

class Collection extends Model
{
    /**
     * The table associated with the model.
     *
     * @var null|string
     */
    protected $table = 'collections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'title', 'type', 'admin_order_by', 'admin_sort', 'admin_per_page',
        'web_order_by', 'web_sort', 'web_per_page', 'description'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [
        'type'
    ];

    /**
     * Get the same type collection instance.
     *
     * @param  string  $type
     * @return \App\Models\Base\Builder|static
     */
    public function byType(string $type): Builder|static
    {
        return $this->where('type', $type);
    }
}
