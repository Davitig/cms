<?php

namespace App\Http\Controllers\Admin;

trait LanguageRelationsActionTrait
{
    /**
     * Perform update or create action on language relation models.
     *
     * @param  string|array  $relations
     * @param  array  $attributes
     * @param  int  $id
     * @return void
     */
    protected function updateOrCreateLanguageRelations(string|array $relations, array $attributes, int $id): void
    {
        foreach ((array) $relations as $relation) {
            if (! method_exists($this->model, $relation)) {
                continue;
            }

            $model = $this->model->$relation(false)->byForeignLanguage($id)->first();

            ! is_null($model)
                ? $model->update($attributes)
                : $this->createLanguageRelations($relation, $attributes, $id);
        }
    }

    /**
     * Create language data.
     *
     * @param  string|array  $relations
     * @param  array  $attributes
     * @param  int  $id
     * @param  bool  $allLanguage
     * @return void
     */
    protected function createLanguageRelations(
        string|array $relations,
        array        $attributes,
        int          $id,
        bool         $allLanguage = false
    ): void
    {
        $languageId = language(true, 'id');

        $attributes[$this->model->getForeignKey()] = $id;

        if (! $allLanguage) {
            $attributes['language_id'] = $languageId;
        }

        foreach ((array) $relations as $relation) {
            $this->model->$relation(false)->create($attributes);
        }
    }
}
