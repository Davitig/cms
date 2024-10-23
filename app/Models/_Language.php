<?php

namespace App\Models;

use App\Models\Base\Model;

class _Language extends Model
{
    /**
     * Set data from a foreign model.
     *
     * @param  \App\Models\Base\Model  $model
     * @return void
     */
    public function setFromForeignModel(Model $model)
    {
        $this->table = $model->getLanguageTable();

        $this->fillable = $model->getLanguageFillable();

        $this->notUpdatable = $model->getLanguageNotUpdatable();
    }
}
