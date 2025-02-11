<?php

namespace App\Http\Requests;

use Cocur\Slugify\Slugify;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    protected function getValidatorInstance(): Validator
    {
        $validator = parent::getValidatorInstance();

        if (method_exists($this, 'beforeValidation')) {
            $this->beforeValidation($validator);
        }

        return $validator;
    }

    /**
     * Slugify specified input value.
     *
     * @param  array  $input
     * @param  string  $key
     * @param  array  $altKeys
     * @return void
     */
    protected function slugifyInput(array &$input, string $key, array $altKeys = []): void
    {
        if (! empty($input[$key])) {
            $input[$key] = (new Slugify)->slugify($input[$key]);
        } elseif (! empty($altKeys)) {
            $values = [];

            foreach ($altKeys as $value) {
                if (isset($input[$value])) {
                    $values[] = $input[$value];
                }
            }

            if (! empty($values)) {
                $input[$key] = (new Slugify)->slugify(implode('-', $values));
            }
        }
    }

    /**
     * Boolify specified input values.
     *
     * @param array $input
     * @param array $params
     * @return void
     */
    protected function boolifyInput(array &$input, array $params): void
    {
        foreach ($params as $param) {
            $input[$param] = (int) ($input[$param] ?? 0);
        }
    }
}
