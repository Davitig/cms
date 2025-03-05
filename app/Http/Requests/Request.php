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
     * Slugify the given key value.
     *
     * @param  string  $key
     * @param  array  $altKeys
     * @return void
     */
    protected function slugifyInput(string $key, array $altKeys = []): void
    {
        if ($value = $this->get($key)) {
            $this->offsetSet($key, (new Slugify)->slugify($value));

            return;
        }

        foreach ($altKeys as $altKey) {
            if ($value = $this->get($altKey)) {
                $this->offsetSet($key, (new Slugify)->slugify($value));

                return;
            }
        }
    }

    /**
     * Boolify the given key value(s).
     *
     * @param  string|array  $keys
     * @return void
     */
    protected function boolifyInput(string|array $keys): void
    {
        foreach (is_array($keys) ? $keys : func_get_args() as $key) {
            $this->offsetSet($key, $this->boolean($key));
        }
    }
}
