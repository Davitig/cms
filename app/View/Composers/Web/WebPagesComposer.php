<?php

namespace App\View\Composers\Web;

use App\Models\Menu;
use App\Models\Page\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class WebPagesComposer
{
    /**
     * The Collection instance of the pages.
     *
     * @var \Illuminate\Database\Eloquent\Collection|null
     */
    protected ?Collection $pages = null;

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('pageItems', $this->getPages());
    }

    /**
     * Get pages by main menu.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPages(): Collection
    {
        $menuId = (new Menu)->where('main', 1)->value('id');

        if (! is_null($menuId) && is_null($this->pages)) {
            $this->pages = (new Page)->forPublic()->menuId($menuId)->positionAsc()->get();
        }

        return make_model_sub_items($this->pages);
    }
}
