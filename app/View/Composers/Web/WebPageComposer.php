<?php

namespace App\View\Composers\Web;

use App\Models\Menu;
use App\Models\Page\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class WebPageComposer
{
    /**
     * The Collection instance of the pages.
     *
     * @var \Illuminate\Support\Collection
     */
    protected Collection $pages;

    /**
     * Create a new view composer instance.
     */
    public function __construct()
    {
        $this->pages = $this->getPages();
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('pageItems', $this->pages);
    }

    /**
     * Get pages by main menu.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPages(): Collection
    {
        $menuId = (new Menu)->where('main', 1)->value('id');

        if (! is_null($menuId)) {
            return make_sub_items(
                (new Page)->forPublic()->menuId($menuId)->positionAsc()->get()
            );
        }

        return new Collection;
    }
}
