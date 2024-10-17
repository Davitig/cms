<?php

namespace Models;

use Models\Abstracts\Model;

class Menu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string|null
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
    protected $notUpdatable = [];
}
