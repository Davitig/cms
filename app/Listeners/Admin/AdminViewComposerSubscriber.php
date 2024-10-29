<?php

namespace App\Listeners\Admin;

use Illuminate\Contracts\View\View;

class AdminViewComposerSubscriber
{
    /**
     * Handle user panel view composer data.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function handleUserPanelComposer(View $view): void
    {
        $view->sitemapXmlTime = null;

        if (file_exists($file = public_path('sitemap.xml'))) {
            $view->sitemapXmlTime = filectime($file);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(): array
    {
        return [
            'composing: admin._partials.user' => 'handleUserPanelComposer',
            'composing: admin._partials.horizontal_menu' => 'handleUserPanelComposer'
        ];
    }
}
