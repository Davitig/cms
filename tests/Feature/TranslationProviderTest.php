<?php

namespace Tests\Feature;

use App\Support\TranslationProvider;
use Database\Factories\TranslationFactory;
use Database\Factories\TranslationLanguageFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranslationProviderTest extends TestCase
{
    use RefreshDatabase;

    public function getFakeData(int $times = 3): array
    {
        $data = [];

        for ($i = 0; $i < $times; $i++) {
            $data[fake()->unique()->word()] = fake()->word();
        }

        return $data;
    }

    public function test_get_value_by_code(): void
    {
        $trans = new TranslationProvider($data = $this->getFakeData());

        $this->assertSame(reset($data), $trans->get(array_key_first($data)));
    }

    public function test_get_from_db_if_not_exists_and_add_in_list(): void
    {
        TranslationFactory::new()->has(
            TranslationLanguageFactory::times(language()->count())
                ->value($value = fake()->word())
                ->sequence(...apply_languages([])),
            'languages'
        )->create(['code' => $code = fake()->unique()->word()]);

        $trans = new TranslationProvider($this->getFakeData(2));

        $trans->get($code);

        $this->assertSame(3, count($trans->all()));

        $this->assertSame($value, $trans->get($code));
    }

    public function test_blacklist(): void
    {
        $trans = new TranslationProvider($this->getFakeData(1));

        $trans->get(fake()->unique()->word());
        $trans->get($code = fake()->unique()->word());
        $trans->get($code);

        $this->assertSame(1, count($trans->all()));

        $this->assertSame(2, count($trans->getBlacklistKeys()));
    }
}
