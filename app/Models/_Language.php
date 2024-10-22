<?php

namespace App\Models;

use App\Models\Eloquent\Model;

class _Language extends Model
{
    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected $notUpdatable = [];

    /**
     * Create a new Language model instance.
     *
     * @param  \App\Models\Eloquent\Model|null  $model
     * @param  array  $attributes
     * @return void
     */
    public function __construct(Model $model = null, array $attributes = [])
    {
        if (! is_null($model)) {
            $this->table = $model->getLanguageTable();

            $this->fillable = $model->getLanguageFillable();

            $this->notUpdatable = $model->getLanguageNotUpdatable();
        }

        parent::__construct($attributes);
    }
}
