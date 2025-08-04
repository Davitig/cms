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
     * @var string|null
     */
    protected ?string $activeLanguage = 'en';

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
        $this->globalLanguageProviderEnabled = false;

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
        $this->languages = array_unique(array_filter(array_merge(
            $this->languages, [$this->activeLanguage]
        )));

        return new LanguageProvider(
            LanguageFactory::new()->languages($this->languages)
                ->main(1, 'en')
                ->when(! is_null($callback), $callback)
                ->create(),
            $this->activeLanguage
        );
    }

    public function test_specified_language_is_active(): void
    {
        $languageProvider = $this->getLanguageProvider();

        $this->assertTrue($languageProvider->isActive($this->activeLanguage));
    }

    public function test_language_is_selected_in_path(): void
    {
        $languageProvider = $this->getLanguageProvider();

        $this->assertTrue($languageProvider->isSelected());
    }

    public function test_language_is_not_selected_in_path(): void
    {
        $this->activeLanguage = null;

        $languageProvider = $this->getLanguageProvider();

        $this->assertFalse($languageProvider->isSelected());
    }

    public function test_specified_language_exists(): void
    {
        $languageProvider = $this->getLanguageProvider();

        $this->assertTrue($languageProvider->exists('en'));
    }

    public function test_specified_language_exists_and_visible(): void
    {
        $languageProvider = $this->getLanguageProvider(fn ($factory) => $factory->visible('cn'));

        $this->assertTrue($languageProvider->visibleExists('cn'));
    }

    public function test_main_language_is_active(): void
    {
        $this->activeLanguage = 'en';

        $languageProvider = $this->getLanguageProvider();

        $this->assertTrue($languageProvider->mainIsActive());
    }

    public function test_get_active_language(): void
    {
        $languageProvider = $this->getLanguageProvider();

        $this->assertSame($this->activeLanguage, $languageProvider->active());
    }

    public function test_get_main_language(): void
    {
        $languageProvider = $this->getLanguageProvider(fn ($factory) => $factory->main(1, 'es'));

        $this->assertSame('es', $languageProvider->main());
    }

    public function test_get_active_language_by_key_type(): void
    {
        $languageProvider = $this->getLanguageProvider();

        $this->assertSame($languageProvider->getActive(), $languageProvider->getBy(true));
    }

    public function test_get_main_language_by_key_type(): void
    {
        $languageProvider = $this->getLanguageProvider();

        $this->assertSame($languageProvider->getMain(), $languageProvider->getBy(false));
    }

    public function test_get_language_by_key_type(): void
    {
        $languageProvider = $this->getLanguageProvider();

        $this->assertSame($languageProvider->get('cn'), $languageProvider->getBy('cn'));
    }

    public function test_get_language_by_key(): void
    {
        $languageProvider = $this->getLanguageProvider();

        $this->assertNotNull($languageProvider->get('es'));
    }

    public function test_get_all_languages(): void
    {
        $languageProvider = $this->getLanguageProvider();

        $this->assertSame($this->languages, $languageProvider->all()->keys()->toArray());
    }

    public function test_get_only_visible_languages(): void
    {
        $languageProvider = $this->getLanguageProvider(fn ($factory) => $factory->visible('en', 'es'));

        $this->assertEquals(['en', 'es'], $languageProvider->allVisible()->keys()->toArray());
    }

    public function test_count_languages(): void
    {
        $languageProvider = $this->getLanguageProvider();

        $this->assertSame(count($this->languages), $languageProvider->count());
    }

    public function test_count_visible_languages(): void
    {
        $languageProvider = $this->getLanguageProvider(fn ($factory) => $factory->visible('en'));

        $this->assertSame(1, $languageProvider->countVisible());
    }

    public function test_language_provider_is_not_empty(): void
    {
        $languageProvider = $this->getLanguageProvider();

        $this->assertFalse($languageProvider->isEmpty());
    }

    public function test_language_provider_is_empty(): void
    {
        $languageProvider = new LanguageProvider(new Collection);

        $this->assertTrue($languageProvider->isEmpty());
    }

    public function test_language_provider_visible_is_not_empty(): void
    {
        $languageProvider = $this->getLanguageProvider(fn ($factory) => $factory->visible('cn'));

        $this->assertFalse($languageProvider->visibleIsEmpty());
    }

    public function test_language_provider_visible_is_empty(): void
    {
        $languageProvider = $this->getLanguageProvider(fn ($factory) => $factory->notVisible());

        $this->assertTrue($languageProvider->visibleIsEmpty());
    }
}
