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
