<?php

namespace App\Models;

use App\Models\Base\Builder;
use App\Models\Base\Model;

class CmsUserRole extends Model
{
    /**
     * The table associated with the model.
     *
     * @var null|string
     */
    protected $table = 'cms_user_roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role', 'full_access'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [];

    /**
     * Add a where 'full_access' to the query.
     *
     * @param  bool|int  $value
     * @return \App\Models\Base\Builder|static
     */
    public function fullAccess(bool|int $value = 1): Builder|static
    {
        return $this->where('full_access', (int) $value);
    }
}
