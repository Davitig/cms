<?php

namespace App\View\Composers\Admin;

use App\Models\Calendar;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class AdminCalendarComposer
{
    /**
     * The Collection instance of the calendar.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected Collection $calendar;

    /**
     * Create a new view composer instance.
     */
    public function __construct()
    {
        $this->calendar = $this->getCalendar();
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('calendarEvents', $this->calendar);
    }

    /**
     * Get the list of calendar.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getCalendar(): Collection
    {
        $start = date('Y-m-d');
        $end = date('Y-m-d', strtotime('+14 days', strtotime($start)));

        return (new Calendar)->active($start, $end)->get();
    }
}
