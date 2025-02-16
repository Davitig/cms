<?php

namespace App\Models\Setting;

use App\Models\Alt\Eloquent\Model;
use App\Models\Alt\Traits\NameValueSettingTrait;

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
     * The options that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [];

    /**
     * The list of default named values.
     *
     * @return array
     */
    public static array $defaultNamedValues = [
        'email' => '',
        'phone' => '',
        'address' => ''
    ];
}
