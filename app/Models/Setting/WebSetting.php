<?php

namespace App\Models\Setting;

use App\Models\Alt\Traits\NameValueSettingTrait;
use Illuminate\Database\Eloquent\Model;

class WebSetting extends Model
{
    use NameValueSettingTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'web_settings';

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
     * Get the list of default named values.
     *
     * @return array
     */
    public function defaultNamedValues(): array
    {
        return [
            'email' => '',
            'phone' => '',
            'address' => ''
        ];
    }
}
