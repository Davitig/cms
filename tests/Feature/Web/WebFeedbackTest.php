<?php

namespace Tests\Feature\Web;

use App\Mail\FeedbackSubmitted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Request;
use Tests\Feature\CreatesLanguageProvider;
use Tests\Feature\InteractsWithDynamicPage;
use Tests\TestCase;

class WebFeedbackTest extends TestCase
{
    use RefreshDatabase, CreatesLanguageProvider, InteractsWithDynamicPage;

    public function test_feedback_page()
    {
        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('feedback')
        );

        $route = $this->getDynamicPageRouteActions($page->slug);

        $response = $this->get($page->slug);

        $this->assertSame([
            'controller' => 'WebFeedbackController', 'method' => 'index'
        ], $this->getActionsFromRoute($route));

        $response->assertOk();
    }

    public function test_feedback_send()
    {
        [$menu, $page] = $this->createPages(
            null, fn ($factory) => $factory->type('feedback')
        );

        $route = $this->getDynamicPageRouteActions($page->slug, Request::METHOD_POST);

        $this->assertSame([
            'controller' => 'WebFeedbackController', 'method' => 'send'
        ], $this->getActionsFromRoute($route));

        Mail::fake();

        Mail::send(new FeedbackSubmitted([]));

        Mail::assertSent(FeedbackSubmitted::class);
    }
}
