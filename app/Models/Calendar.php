<?php

namespace App\Models;

use App\Models\Alt\Eloquent\Builder;
use App\Models\Alt\Eloquent\Model;
use Illuminate\Http\Request;

class Calendar extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'calendar';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cms_user_id', 'title', 'description', 'color', 'start', 'end', 'time_start', 'time_end'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [];

    /**
     * The list of the available colors.
     *
     * @var array
     */
    protected array $colors = [
        'red', 'blue', 'green', 'orange', 'turquoise', 'purple', 'black', 'gray'
    ];

    /**
     * Get the mutated "start" attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getStartAttribute(string $value): string
    {
        $value = date('Y-m-d', strtotime($value));

        if (! is_null($this->time_start)) {
            return $value . 'T' . $this->time_start;
        }

        return $value;
    }

    /**
     * Get the mutated "end" attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getEndAttribute(string $value): string
    {
        $value = date('Y-m-d', strtotime($value));

        if (! is_null($this->time_end)) {
            return $value . 'T' . $this->time_end;
        }

        return $value;
    }

    /**
     * Get a random color.
     *
     * @return string
     */
    public function getRandomColor(): string
    {
        return $this->colors[rand(0, count($this->colors) - 1)];
    }

    /**
     * Add a where 'cms_user_id' clause to the query.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  int  $userId
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeByUserId(Builder $query, int $userId): Builder
    {
        return $query->where('cms_user_id', $userId);
    }

    /**
     * Build a query based on active dates.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  string|null  $start
     * @param  string|null  $end
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeActive(Builder $query, ?string $start = null, ?string $end = null): Builder
    {
        if (is_null($start)) {
            $start = date('Y-m') . '-01';

            $start = date('Y-m-d', strtotime('-10 days', strtotime($start)));
        }

        if (is_null($end)) {
            $end = date('Y-m-d', strtotime('+50 days', strtotime($start)));
        }

        return $query->whereNotNull('start')->whereBetween('start', [$start, $end]);
    }

    /**
     * Build a query based on inactive dates.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->whereNull('start');
    }

    /**
     * Update the specified calendar event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool|int
     */
    public function updateEvent(Request $request): bool|int
    {
        $input = $request->all(['title', 'description', 'color']);

        if ($request->filled('start') || $request->filled('end')) {
            $dates = $this->filterDates($request->all(['start', 'end']));

            $input = array_merge($input, $dates);
        }

        return $this->update($input);
    }

    /**
     * Filter event dates.
     *
     * @param  array  $dates
     * @return array
     */
    protected function filterDates(array $dates): array
    {
        $dates['time_start'] = $dates['time_end'] = null;
        $start = $dates['start'];
        $end = $dates['end'];

        if ($hasTimeStart = strpos($dates['start'], 'T')) {
            $dates['time_start'] = substr($dates['start'], $hasTimeStart + 1);

            $dates['start'] = substr($dates['start'], 0, $hasTimeStart);
        }

        if ($hasTimeEnd = strpos($dates['end'], 'T')) {
            $dates['time_end'] = substr($dates['end'], $hasTimeEnd + 1);

            if ($start == $end) {
                if ($hasTimeStart) {
                    $dates['time_end'] = date(
                        'H:i:s',
                        strtotime('+1 hour', strtotime($dates['time_start']))
                    );
                }
            }

            $dates['end'] = substr($dates['end'], 0, $hasTimeEnd);
        } else {
            if (is_null($end)) {
                $dates['end'] = $dates['start'];
            }

            if ($hasTimeStart) {
                $dates['time_end'] = date(
                    'H:i:s',
                    strtotime('+1 hour', strtotime($dates['time_start']))
                );
            }
        }

        return $dates;
    }
}
