<?php

namespace Tests\Feature\Web;

use App\View\Composers\Web\WebBreadcrumbComposer;
use App\View\Composers\Web\WebCurrentDataComposer;
use App\View\Composers\Web\WebPagesComposer;
use App\View\Composers\Web\WebSettingsComposer;
use App\View\Composers\Web\WebTranslationsComposer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use stdClass;
use Tests\TestCase;

class WebComposersTest extends TestCase
{
    protected function testWebComposer(string $composerClass, ...$someOfArgs)
    {
        $composer = $this->app->make($composerClass);

        $mock = $this->mock(View::class);

        $mock->shouldReceive('with')->once()->withSomeOfArgs(...$someOfArgs);

        $composer->compose($mock);
    }

    public function test_web_settings_composer()
    {
        $this->testWebComposer(WebSettingsComposer::class, 'settings');
    }

    public function test_web_translations_composer()
    {
        $this->testWebComposer(WebTranslationsComposer::class, 'trans');
    }

    public function test_web_current_data_composer()
    {
        $composer = $this->app->make(WebCurrentDataComposer::class);

        $mock = $this->mock(View::class);

        $composer->compose($mock);

        $this->assertIsObject($mock->current);
    }

    public function test_web_pages_composer()
    {
        $this->testWebComposer(WebPagesComposer::class, 'pageItems');
    }

    public function test_web_breadcrumb_composer()
    {
        $composer = $this->app->make(WebBreadcrumbComposer::class);

        $mock = $this->mock(View::class);

        $mock->current = new stdClass;

        $composer->compose($mock);

        $this->assertInstanceOf(Collection::class, $this->app['breadcrumb']);
    }
}
