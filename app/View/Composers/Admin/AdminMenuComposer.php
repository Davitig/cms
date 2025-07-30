<?php

namespace App\View\Composers\Admin;

use App\Models\Menu;
use Illuminate\Contracts\View\View;

class AdminMenuComposer
{
    /**
     * The Menu instance.
     *
     * @var \App\Models\Menu|null
     */
    protected ?Menu $menu;

    /**
     * Create a new view composer instance.
     */
    public function __construct()
    {
        $this->menu = $this->getMenu();
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('menu', $this->menu);
    }

    /**
     * Get the main menu.
     *
     * @return \App\Models\Menu|null
     */
    protected function getMenu(): ?Menu
    {
        return (new Menu)->whereMain(1)->union((new Menu)->whereMain(0))->first();
    }
}
