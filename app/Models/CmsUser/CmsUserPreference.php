<?php

namespace App\Models\CmsUser;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CmsUserPreference extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cms_user_id', 'horizontal_menu', 'ajax_form', 'roles_list_view'
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
