<?php

namespace App\View\Composers\Admin;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\View\View;

class AdminMenuComposer
{
    /**
     * The Collection instance of the menus.
     *
     * @var \Illuminate\Database\Eloquent\Collection|null
     */
    protected ?Collection $menus = null;

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('menus', $this->getMenus());
    }

    /**
     * Get the menus.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getMenus(): Collection
    {
        if (is_null($this->menus)) {
            $this->menus = (new Menu)->get();
        }

        return $this->menus;
    }
}
