<?php

namespace App\View\Composers\Admin;

use Illuminate\Contracts\View\View;

class AdminSitemapXmlComposer
{
    /**
     * Indicates inode change time of the file.
     *
     * @var string|null
     */
    protected ?string $sitemapXmlTime = null;

    /**
     * Create a new view composer instance.
     */
    public function __construct()
    {
        $this->sitemapXmlTime = null;

        if (file_exists($file = public_path('sitemap.xml'))) {
            $this->sitemapXmlTime = filectime($file);
        }
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('sitemapXmlTime', $this->sitemapXmlTime);
    }
}
