<?php

namespace App\Models\Setting;

use App\Concerns\Models\HasNameValueSetting;
use App\Contracts\Models\Setting;
use Illuminate\Database\Eloquent\Model;

class MetaSetting extends Model implements Setting
{
    use HasNameValueSetting;

    /**
     * The options that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language_id', 'name', 'value'
    ];

    /**
     * Determine if the model has languages' relation.
     *
     * @return bool
     */
    public function hasLanguages(): bool
    {
        return true;
    }

    /**
     * Get the list of default name values.
     *
     * @return array
     */
    public function defaultNameValues(): array
    {
        return [
            'site_name' => null,
            'title' => null,
            'description' => null,
            'image' => null
        ];
    }
}
