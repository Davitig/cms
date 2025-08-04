<?php

namespace App\Contracts\Models;

use Illuminate\Support\Collection;

interface Setting
{
    /**
     * Get the result of the settings record.
     *
     * @param  mixed  $currentLang
     * @return \Illuminate\Support\Collection
     */
    public function getSettings(mixed $currentLang = true): Collection;
}
