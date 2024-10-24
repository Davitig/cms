<?php

namespace App\Models;

use App\Models\Base\Model;

class Note extends Model
{
    /**
     * The table associated with the model.
     *
     * @var null|string
     */
    protected $table = 'notes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'content'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [];
}
