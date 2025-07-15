<?php

namespace App\View\Composers\Admin;

use App\Models\Menu;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class AdminMenuComposer
{
    /**
     * The Collection instance of the menus.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected Collection $items;

    /**
     * Create a new view composer instance.
     *
     */
    public function __construct()
    {
        $this->items = $this->getMenus();
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('menus', $this->items);
    }

    /**
     * Get the menus.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getMenus(): Collection
    {
        return (new Menu)->whereMain(1)
            ->union((new Menu)->whereMain(0))
            ->limit(1)
            ->get();
    }
}
