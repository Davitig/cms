<?php

namespace Tests\Feature\Web;

use App\Mail\FeedbackSubmitted;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Request;
use Tests\Feature\DynamicRoutesTrait;
use Tests\TestCase;

class WebFeedbackTest extends TestCase
{
    use DynamicRoutesTrait;

    public function test_feedback_page()
    {
        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('feedback')
        );

        $route = $this->getDynamicPageRouteActions($page->slug);

        $response = $this->get($page->slug);

        $page->delete();
        $menu->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebFeedbackController', 'method' => 'index'
        ]);

        $response->assertOk();
    }

    public function test_feedback_send()
    {
        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('feedback')
        );

        $route = $this->getDynamicPageRouteActions($page->slug, Request::METHOD_POST);

        $page->delete();
        $menu->delete();

        $this->assertSame($this->getActionsFromRoute($route), [
            'controller' => 'WebFeedbackController', 'method' => 'send'
        ]);

        Mail::fake();

        Mail::send(new FeedbackSubmitted([]));

        Mail::assertSent(FeedbackSubmitted::class);
    }
}
