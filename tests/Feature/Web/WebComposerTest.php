<?php

namespace Tests\Feature\Web;

use App\View\Composers\Web\WebBreadcrumbComposer;
use App\View\Composers\Web\WebCurrentDataComposer;
use App\View\Composers\Web\WebPageComposer;
use App\View\Composers\Web\WebSettingComposer;
use App\View\Composers\Web\WebTranslationComposer;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use stdClass;
use Tests\TestCase;

class WebComposerTest extends TestCase
{
    use RefreshDatabase;

    protected function testComposer(string $composerClass, ...$someOfArgs): void
    {
        $composer = $this->app->make($composerClass);

        $mock = $this->mock(View::class);

        $mock->expects('with')->once()->withSomeOfArgs(...$someOfArgs);

        $composer->compose($mock);
    }

    public function test_web_settings_composer()
    {
        $this->testComposer(WebSettingComposer::class, 'settings');
    }

    public function test_web_translations_composer()
    {
        $this->testComposer(WebTranslationComposer::class, 'trans');
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
        $this->testComposer(WebPageComposer::class, 'pageItems');
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
