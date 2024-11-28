<?php

namespace App\Models;

use App\Models\Alt\Eloquent\Builder;
use App\Models\Alt\Eloquent\Model;

class CmsUserRole extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string|null
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
     * @return \App\Models\Alt\Eloquent\Builder|static
     */
    public function fullAccess(bool|int $value = 1): Builder|static
    {
        return $this->where('full_access', (int) $value);
    }

    /**
     * Add a where not 'full_access' to the query.
     *
     * @return \App\Models\Alt\Eloquent\Builder|static
     */
    public function customAccess(): Builder|static
    {
        return $this->where('full_access', 0);
    }
}
