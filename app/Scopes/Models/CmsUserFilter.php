<?php

namespace App\Scopes\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

readonly class CmsUserFilter
{
    /**
     * Create a new filter instance.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function __construct(private Request $request) {}

    /**
     * Apply the query parameters to the Eloquent builder instance.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function __invoke(Builder $query): Builder
    {
        $suspendValue = (int) $this->request->boolean('suspended');

        return $query->when($this->request->get('name'), function ($q, $value) {
            return $q->whereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$value}%"]);
        })->when($this->request->get('email'), function ($q, $value) {
            return $q->whereLike('email', "%{$value}%");
        })->when($this->request->get('role'), function ($q, $value) {
            return $q->where('cms_user_role_id', $value);
        })->when($this->request->filled('suspended'), function ($q) use ($suspendValue) {
            return $q->where('suspended', $suspendValue);
        });
    }
}
