<?php

namespace App\Models\Setting;

use App\Models\Alt\Traits\NameValueSettingTrait;
use Illuminate\Database\Eloquent\Model;

class WebSetting extends Model
{
    use NameValueSettingTrait;

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
            'email' => '',
            'phone' => '',
            'address' => ''
        ];
    }
}
