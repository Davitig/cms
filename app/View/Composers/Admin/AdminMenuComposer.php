<?php

namespace App\View\Composers\Admin;

use App\Models\Menu;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;

class AdminMenuComposer
{
    /**
     * The Collection instance of the menus.
     *
     * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected LengthAwarePaginator $menus;

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
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function getMenus(): LengthAwarePaginator
    {
        return (new Menu)->paginate(50);
    }
}
