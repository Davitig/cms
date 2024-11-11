<?php

namespace App\View\Composers\Admin;

use App\Models\Calendar;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\View\View;

class AdminCalendarComposer
{
    /**
     * The Collection instance of the calendar.
     *
     * @var \Illuminate\Database\Eloquent\Collection|null
     */
    protected ?Collection $calendar = null;

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('calendarEvents', $this->getCalendar());
    }

    /**
     * Get the list of calendar.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getCalendar(): Collection
    {
        if (is_null($this->calendar)) {
            $start = date('Y-m-d');
            $end = date('Y-m-d', strtotime('+7 days', strtotime($start)));

            $this->calendar = (new Calendar)->active($start, $end)->get();
        }

        return $this->calendar;
    }
}
