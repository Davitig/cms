<?php

namespace App\Models;

use App\Models\Eloquent\Builder;
use App\Models\Eloquent\Model;
use App\Models\Traits\FileTrait;

class EventFile extends Model
{
    use FileTrait;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'event_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id', 'position', 'visible'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [
        'event_id'
    ];

    /**
     * Related database table name used by the Language model.
     *
     * @var string
     */
    protected string $languageTable = 'event_file_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected array $languageFillable = [
        'event_file_id', 'language_id', 'title', 'file'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected array $languageNotUpdatable = [
        'event_file_id', 'language_id'
    ];

    /**
     * Get the mutated file default attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getFileDefaultAttribute($value): string
    {
        return $value ?: asset('assets/libs/images/image-1.jpg');
    }

    /**
     * Add a where foreign id clause to the query.
     *
     * @param  int  $foreignId
     * @return \App\Models\Eloquent\Builder|static
     */
    public function byForeign(int $foreignId): Builder|static
    {
        return $this->where('event_id', $foreignId);
    }
}
