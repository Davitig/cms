<?php

namespace App\Models;

use App\Models\Alt\Base\Model;
use App\Models\Alt\Traits\PositionableTrait;

class Language extends Model
{
    use PositionableTrait;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'languages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language', 'visible', 'position', 'short_name', 'full_name'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [];
}
