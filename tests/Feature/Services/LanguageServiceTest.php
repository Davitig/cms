<?php

namespace Tests\Feature\Services;

use App\Models\Language;
use App\Services\LanguageService;
use Closure;
use Database\Factories\LanguageFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;

class LanguageServiceTest extends TestCase
{
    /**
     * The active language.
     *
     * @var string
     */
    protected string $activeLanguage = 'es';

    /**
     * The active language.
     *
     * @var array
     */
    protected array $languages = ['en', 'es', 'fr'];

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        (new Language)->newQuery()->delete();

        DB::update('ALTER TABLE languages AUTO_INCREMENT = 1');

        if (! empty($envActiveLanguage = (string) getenv('lang_active'))) {
            $this->activeLanguage = $envActiveLanguage;
        }
    }

    /**
     * This method is called after each test.
     */
    protected function tearDown(): void
    {
        (new Language)->newQuery()->delete();

        parent::tearDown();
    }

    /**
     * Get the language service.
     *
     * @param  \Closure|null  $callback
     * @return \App\Services\LanguageService
     */
    protected function getLanguageService(?Closure $callback = null): LanguageService
    {
        $this->languages = array_unique(array_merge($this->languages, [$this->activeLanguage]));

        return new LanguageService(
            LanguageFactory::new()->languages($this->languages)
                ->when(! is_null($callback), $callback)
                ->create(),
            $this->activeLanguage . '/foo/bar'
        );
    }

    public function test_specified_language_is_active(): void
    {
        $service = $this->getLanguageService();

        $this->assertTrue($service->isActive($this->activeLanguage));
    }

    public function test_language_is_selected_in_path(): void
    {
        $service = $this->getLanguageService();

        $this->assertTrue($service->isSelected());
    }

    public function test_language_is_not_selected_in_path(): void
    {
        $this->activeLanguage = '';

        $service = $this->getLanguageService();

        $this->assertFalse($service->isSelected());
    }

    public function test_specified_language_exists(): void
    {
        $service = $this->getLanguageService();

        $this->assertTrue($service->exists('en'));
    }

    public function test_specified_language_exists_and_visible(): void
    {
        $service = $this->getLanguageService(fn ($factory) => $factory->visible('fr'));

        $this->assertTrue($service->visibleExists('fr'));
    }

    public function test_main_language_is_active(): void
    {
        $this->activeLanguage = 'en';

        $service = $this->getLanguageService();

        $this->assertTrue($service->mainIsActive());
    }

    public function test_get_active_language(): void
    {
        $service = $this->getLanguageService();

        $this->assertSame($this->activeLanguage, $service->active());
    }

    public function test_get_main_language(): void
    {
        $service = $this->getLanguageService(fn ($factory) => $factory->main(1, 'es'));

        $this->assertSame('es', $service->main());
    }

    public function test_get_active_language_by_key_type(): void
    {
        $service = $this->getLanguageService();

        $this->assertSame($service->getActive(), $service->getBy(true));
    }

    public function test_get_main_language_by_key_type(): void
    {
        $service = $this->getLanguageService();

        $this->assertSame($service->getMain(), $service->getBy(false));
    }

    public function test_get_language_by_key_type(): void
    {
        $service = $this->getLanguageService();

        $this->assertSame($service->get('fr'), $service->getBy('fr'));
    }

    public function test_get_language_by_key(): void
    {
        $service = $this->getLanguageService();

        $this->assertNotNull($service->get('es'));
    }

    public function test_get_all_languages(): void
    {
        $service = $this->getLanguageService();

        $this->assertSame($this->languages, $service->all()->keys()->toArray());
    }

    public function test_get_only_visible_languages(): void
    {
        $service = $this->getLanguageService(fn ($factory) => $factory->visible('en', 'es'));

        $this->assertEquals(['en', 'es'], $service->allVisible()->keys()->toArray());
    }

    public function test_count_languages(): void
    {
        $service = $this->getLanguageService();

        $this->assertSame(count($this->languages), $service->count());
    }

    public function test_count_visible_languages(): void
    {
        $service = $this->getLanguageService(fn ($factory) => $factory->visible('en'));

        $this->assertSame(1, $service->countVisible());
    }

    public function test_language_service_is_not_empty(): void
    {
        $service = $this->getLanguageService();

        $this->assertFalse($service->isEmpty());
    }

    public function test_language_service_is_empty(): void
    {
        $service = new LanguageService(new Collection, '/');

        $this->assertTrue($service->isEmpty());
    }

    public function test_language_service_visible_is_not_empty(): void
    {
        $service = $this->getLanguageService(fn ($factory) => $factory->visible('fr'));

        $this->assertFalse($service->visibleIsEmpty());
    }

    public function test_language_service_visible_is_empty(): void
    {
        $service = $this->getLanguageService(fn ($factory) => $factory->notVisible());

        $this->assertTrue($service->visibleIsEmpty());
    }
}
