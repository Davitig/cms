<?php

namespace App\Models;

use App\Models\Alt\Eloquent\Builder;
use App\Models\Alt\Eloquent\Model;
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

    /**
     * Add a where "language" clause to the query.
     *
     * @param  string  $language
     * @return \App\Models\Alt\Eloquent\Builder|static
     */
    public function whereLanguage(string $language): Builder|static
    {
        return $this->where('language', $language);
    }

    /**
     * Add a where "visible" clause to the query.
     *
     * @param  int  $value
     * @return \App\Models\Alt\Eloquent\Builder|static
     */
    public function whereVisible(int $value = 1): Builder|static
    {
        return $this->where('visible', $value);
    }
}
