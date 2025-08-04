<?php

namespace App\Models\Language;

use App\Concerns\Models\HasNameValueSetting;
use App\Contracts\Models\Setting;
use Illuminate\Database\Eloquent\Model;

class LanguageSetting extends Model implements Setting
{
    use HasNameValueSetting;

    /**
     * The options that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'value'
    ];

    /**
     * Determine if the model has languages' relation.
     *
     * @return bool
     */
    public function hasLanguages(): bool
    {
        return false;
    }

    /**
     * Get the list of default name values.
     *
     * @return array
     */
    public function defaultNameValues(): array
    {
        return [
            'down_without_language' => 0,
            'exclude_main_language_from_url' => 0,
            'exclude_single_language_from_url' => 0,
            'redirect_from_main' => 0,
            'redirect_to_main' => 0
        ];
    }
}
