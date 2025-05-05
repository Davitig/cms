<?php

namespace App\Models;

use App\Models\Alt\Traits\QueriesTrait;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use QueriesTrait;

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
}
