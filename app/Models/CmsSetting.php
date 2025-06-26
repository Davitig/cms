<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CmsSetting extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'cms_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'horizontal_menu', 'roles_list_view',
    ];

    /**
     * Add a where 'cms_user_role_id' clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCmsUserId(Builder $query, int $value): Builder
    {
        return $query->where('cms_user_id', $value);
    }
}
