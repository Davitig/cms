<?php

namespace App\Models;

use App\Models\Abstracts\Model;

class Collection extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'collections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'title', 'type', 'admin_order_by', 'admin_sort', 'admin_per_page', 'web_order_by', 'web_sort', 'web_per_page', 'description'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected $notUpdatable = [
        'type'
    ];

    /**
     * Get the same type collection instance.
     *
     * @param  string  $type
     * @return $this
     */
    public function byType($type)
    {
        return $this->where('type', $type);
    }
}
