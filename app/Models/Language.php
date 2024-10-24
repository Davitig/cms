<?php

namespace App\Models;

use App\Models\Base\Model;

class Language extends Model
{
    /**
     * The table associated with the model.
     *
     * @var null|string
     */
    protected $table = 'languages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language', 'main', 'short_name', 'full_name'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [];
}
