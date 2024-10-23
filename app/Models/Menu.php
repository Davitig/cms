<?php

namespace App\Models;

use App\Models\Base\Model;

class Menu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var null|string
     */
    protected $table = 'menus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'main', 'title', 'description'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [];
}
