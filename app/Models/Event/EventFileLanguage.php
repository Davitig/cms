<?php

namespace App\Models\Event;

use App\Models\Base\Builder;
use App\Models\Base\Model;
use App\Models\Traits\LanguageTrait;

class EventFileLanguage extends Model
{
    use LanguageTrait;

    /**
     * The table associated with the model.
     *
     * @var null|string
     */
    protected $table = 'event_file_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected $fillable = [
        'event_file_id', 'language_id', 'title', 'file'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected array $notUpdatable = [
        'event_file_id', 'language_id'
    ];

    /**
     * Add a where "event_file_id" clause to the query.
     *
     * @param  int  $id
     * @return \App\Models\Base\Builder|static
     */
    public function foreignId(int $id): Builder|static
    {
        return $this->where('event_file_id', $id);
    }
}