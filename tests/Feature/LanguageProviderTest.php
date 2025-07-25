<?php

namespace Tests\Feature;

use App\Support\LanguageProvider;
use Closure;
use Database\Factories\LanguageFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguageProviderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The active language.
     *
     * @var string
     */
    protected string $activeLanguage = 'en';

    /**
     * The active language.
     *
     * @var array
     */
    protected array $languages = ['en', 'es', 'cn'];

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        $this->globalLanguageProvider = false;

        parent::setUp();

        if (! empty($envActiveLanguage = (string) getenv('lang_active'))) {
            $this->activeLanguage = $envActiveLanguage;
        }
    }

    /**
     * Get the language provider.
     *
     * @param  \Closure|null  $callback
     * @return \App\Support\LanguageProvider
     */
    protected function getLanguageProvider(?Closure $callback = null): LanguageProvider
    {
        $this->languages = array_unique(array_merge($this->languages, [$this->activeLanguage]));

        return new LanguageProvider(
            LanguageFactory::new()->languages($this->languages)
                ->main(1, 'en')
                ->when(! is_null($callback), $callback)
                ->create(),
            $this->activeLanguage . '/foo/bar'
        );
    }

    public function test_specified_language_is_active(): void
    {
        $service = $this->getLanguageProvider();

        $this->assertTrue($service->isActive($this->activeLanguage));
    }

    public function test_language_is_selected_in_path(): void
    {
        $service = $this->getLanguageProvider();

        $this->assertTrue($service->isSelected());
    }

    public function test_language_is_not_selected_in_path(): void
    {
        $this->activeLanguage = '';

        $service = $this->getLanguageProvider();

        $this->assertFalse($service->isSelected());
    }

    public function test_specified_language_exists(): void
    {
        $service = $this->getLanguageProvider();

        $this->assertTrue($service->exists('en'));
    }

    public function test_specified_language_exists_and_visible(): void
    {
        $service = $this->getLanguageProvider(fn ($factory) => $factory->visible('cn'));

        $this->assertTrue($service->visibleExists('cn'));
    }

    public function test_main_language_is_active(): void
    {
        $this->activeLanguage = 'en';

        $service = $this->getLanguageProvider();

        $this->assertTrue($service->mainIsActive());
    }

    public function test_get_active_language(): void
    {
        $service = $this->getLanguageProvider();

        $this->assertSame($this->activeLanguage, $service->active());
    }

    public function test_get_main_language(): void
    {
        $service = $this->getLanguageProvider(fn ($factory) => $factory->main(1, 'es'));

        $this->assertSame('es', $service->main());
    }

    public function test_get_active_language_by_key_type(): void
    {
        $service = $this->getLanguageProvider();

        $this->assertSame($service->getActive(), $service->getBy(true));
    }

    public function test_get_main_language_by_key_type(): void
    {
        $service = $this->getLanguageProvider();

        $this->assertSame($service->getMain(), $service->getBy(false));
    }

    public function test_get_language_by_key_type(): void
    {
        $service = $this->getLanguageProvider();

        $this->assertSame($service->get('cn'), $service->getBy('cn'));
    }

    public function test_get_language_by_key(): void
    {
        $service = $this->getLanguageProvider();

        $this->assertNotNull($service->get('es'));
    }

    public function test_get_all_languages(): void
    {
        $service = $this->getLanguageProvider();

        $this->assertSame($this->languages, $service->all()->keys()->toArray());
    }

    public function test_get_only_visible_languages(): void
    {
        $service = $this->getLanguageProvider(fn ($factory) => $factory->visible('en', 'es'));

        $this->assertEquals(['en', 'es'], $service->allVisible()->keys()->toArray());
    }

    public function test_count_languages(): void
    {
        $service = $this->getLanguageProvider();

        $this->assertSame(count($this->languages), $service->count());
    }

    public function test_count_visible_languages(): void
    {
        $service = $this->getLanguageProvider(fn ($factory) => $factory->visible('en'));

        $this->assertSame(1, $service->countVisible());
    }

    public function test_language_service_is_not_empty(): void
    {
        $service = $this->getLanguageProvider();

        $this->assertFalse($service->isEmpty());
    }

    public function test_language_service_is_empty(): void
    {
        $service = new LanguageProvider(new Collection, '/');

        $this->assertTrue($service->isEmpty());
    }

    public function test_language_service_visible_is_not_empty(): void
    {
        $service = $this->getLanguageProvider(fn ($factory) => $factory->visible('cn'));

        $this->assertFalse($service->visibleIsEmpty());
    }

    public function test_language_service_visible_is_empty(): void
    {
        $service = $this->getLanguageProvider(fn ($factory) => $factory->notVisible());

        $this->assertTrue($service->visibleIsEmpty());
    }
}
