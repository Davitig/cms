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
    protected Collection $menus;

    /**
     * Create a new view composer instance.
     *
     */
    public function __construct()
    {
        $this->menus = $this->getMenus();
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('menus', $this->menus);
    }

    /**
     * Get the menus.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getMenus(): Collection
    {
        return (new Menu)->get();
    }
}
