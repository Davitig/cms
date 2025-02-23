<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;

trait LanguageRelationsTrait
{
    /**
     * Perform update or create action on language relation models.
     *
     * @param  string|array  $relations
     * @param  array  $attributes
     * @param  \Illuminate\Database\Eloquent\Model|int  $model
     * @return array
     */
    protected function updateOrCreateLanguageRelations(
        string|array $relations,
        array        $attributes,
        Model|int    $model
    ): array
    {
        if (is_int($model)) {
            $id = $model;

            $model = $this->model;
        } else {
            $id = $model->id;
        }

        $data = [];

        foreach ((array) $relations as $relation) {
            if (! method_exists($model, $relation)) {
                continue;
            }

            $relationModel = $model->$relation(false)->byForeignLanguage($id)->first();

            if (is_null($relationModel)) {
                $data += $this->createLanguageRelations($relation, $attributes, $id, false);
            } else {
                $relationModel->update($attributes);

                $data[$relation][$relationModel->language_id] = $relationModel;
            }
        }

        return $data;
    }

    /**
     * Create language data.
     *
     * @param  string|array  $relations
     * @param  array  $attributes
     * @param  \Illuminate\Database\Eloquent\Model|int  $model
     * @param  bool  $allLanguages
     * @return array
     */
    protected function createLanguageRelations(
        string|array $relations,
        array        $attributes,
        Model|int    $model,
        bool         $allLanguages = true
    ): array
    {
        if (is_int($model)) {
            $id = $model;

            $model = $this->model;
        } else {
            $id = $model->id;
        }

        $languageId = language(true, 'id');

        $attributes[$model->getForeignKey()] = $id;

        if (! $allLanguages) {
            $attributes['language_id'] = $languageId;
        }

        $data = [];

        foreach ((array) $relations as $relation) {
            $langModels = $model->$relation(false)->create($attributes);

            if ($allLanguages) {
                $data[$relation] = $langModels;
            } else {
                $data[$relation][$languageId] = $langModels;
            }
        }

        return $data;
    }
}
