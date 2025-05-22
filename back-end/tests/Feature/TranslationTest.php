<?php

namespace Tests\Feature;

use App\Models\Pack;
use App\Models\Translation;
use App\Models\User;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class TranslationTest extends TestCase
{
    use TestHelpers;

    #[Test]
    #[Group('translation')]
    public function guest_and_user_can_view_translation_index(): void
    {
        // Arrange
        $pack = Pack::factory()->create();
        $translations = Translation::factory(5)->create(['pack_id' => $pack->id]);
        $otherTranslations = Translation::factory(5)->create(['pack_id' => Pack::factory()->create()->id]);

        // Act
        $response = $this->getJson(route('api.packs.translations.index', ['pack' => $pack->id]));

        // Assert
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
        $translations->each(function ($translation) use ($response) {
            $response->assertJsonFragment([
                'from_translation' => $translation->from_translation,
                'to_translation' => $translation->to_translation,
            ]);
        });
        $otherTranslations->each(function ($translation) use ($response, $pack) {
            $response->assertJsonMissing([
                'from_translation' => $translation->from_translation,
                'to_translation' => $translation->to_translation,
            ]);
        });

        // Arrange
        $this->authenticateUser();

        // Act
        $response = $this->getJson(route('api.packs.translations.index', ['pack' => $pack->id]));

        // Assert
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
        $translations->each(function ($translation) use ($response) {
            $response->assertJsonFragment([
                'from_translation' => $translation->from_translation,
                'to_translation' => $translation->to_translation,
            ]);
        });
        $otherTranslations->each(function ($translation) use ($response) {
            $response->assertJsonMissing([
                'from_translation' => $translation->from_translation,
                'to_translation' => $translation->to_translation,
            ]);
        });
    }

    #[Test]
    #[Group('translation')]
    public function guest_and_user_can_limit_translation_index(): void
    {
        // Arrange
        $pack = Pack::factory()->create();
        $translations = Translation::factory(5)->create(['pack_id' => $pack->id]);

        // Act
        $response = $this->getJson(route('api.packs.translations.index', ['pack' => $pack->id, 'count' => 3]));

        // Assert
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');

        // Arrange
        $this->authenticateUser();

        // Act
        $response = $this->getJson(route('api.packs.translations.index', ['pack' => $pack->id, 'count' => 3]));

        // Assert
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    #[Test]
    #[Group('translation')]
    public function guest_can_not_create_translation(): void
    {
        // Arrange
        $pack = Pack::factory()->create();
        $translationData = [
            'from_translation' => 'Hello',
            'to_translation' => 'Hola',
        ];

        // Act
        $response = $this->postJson(route('api.packs.translations.store', ['pack' => $pack->id]), $translationData);

        // Assert
        $response->assertStatus(401);
        $this->assertDatabaseMissing('translations', [
            'from_translation' => $translationData['from_translation'],
            'to_translation' => $translationData['to_translation'],
        ]);
    }

    #[Test]
    #[Group('translation')]
    #[DataProvider('validTranslationData')]
    public function user_can_create_translation_with_valid_data($translationData): void
    {
        // Arrange
        $user = $this->authenticateUser();
        $pack = Pack::factory()->create([
            'user_id' => $user->id,
        ]);

        // Act
        $response = $this->postJson(route('api.packs.translations.store', ['pack' => $pack->id]), $translationData);

        // Assert
        $response->assertStatus(201)
            ->assertJsonFragment([
                'from_translation' => strtolower($translationData['from_translation']),
                'to_translation' => strtolower($translationData['to_translation']),
            ]);
        $this->assertDatabaseHas('translations', [
            'from_translation' => $translationData['from_translation'],
            'to_translation' => $translationData['to_translation'],
        ]);
    }

    #[Test]
    #[Group('translation')]
    #[DataProvider('invalidTranslationData')]
    public function user_can_not_create_translation_with_invalid_data($translationData, $errorMessages): void
    {
        // Arrange
        $user = $this->authenticateUser();
        $pack = Pack::factory()->create([
            'user_id' => $user->id,
        ]);

        // Act
        $response = $this->postJson(route('api.packs.translations.store', ['pack' => $pack->id]), $translationData);

        // Assert
        $response->assertStatus(422)
            ->assertJsonFragment($errorMessages);

        $this->assertDatabaseMissing('translations', $translationData);
    }

    #[Test]
    #[Group('translation')]
    public function user_can_not_create_translation_for_unauthorized_pack(): void
    {
        // Arrange
        $this->authenticateUser();
        $pack = Pack::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);
        $translationData = [
            'from_translation' => 'Hello',
            'to_translation' => 'Hola',
        ];

        // Act
        $response = $this->postJson(route('api.packs.translations.store', ['pack' => $pack->id]), $translationData);

        // Assert
        $response->assertStatus(403);
        $this->assertDatabaseMissing('translations', [
            'from_translation' => $translationData['from_translation'],
            'to_translation' => $translationData['to_translation'],
        ]);
    }

    #[Test]
    #[Group('translation')]
    public function user_can_not_create_translation_with_existing_data(): void
    {
        // Arrange
        $user = $this->authenticateUser();
        $pack = Pack::factory()->create([
            'user_id' => $user->id,
        ]);
        $translation = Translation::factory()->create([
            'pack_id' => $pack->id,
            'from_translation' => 'Hello',
            'to_translation' => 'Holla',
        ]);

        // Act
        $response = $this->postJson(route('api.packs.translations.store', ['pack' => $pack->id]), [
            'from_translation' => $translation->from_translation,
            'to_translation' => $translation->to_translation,
        ]);

        // Assert
        $response->assertStatus(422)
            ->assertJsonFragment([
                'message' => 'Validation failed',
                'pack_id' => [
                    'Translation already exists',
                ],
            ]);
    }

    #[Test]
    #[Group('translation')]
    #[DataProvider('validTranslationData')]
    public function user_can_update_translation_with_valid_data($translationData): void
    {
        // Arrange
        $user = $this->authenticateUser();
        $pack = Pack::factory()->create([
            'user_id' => $user->id,
        ]);
        $translation = Translation::factory()->create([
            'pack_id' => $pack->id,
            'from_translation' => 'Hello',
            'to_translation' => 'Hallo',
        ]);

        // Act
        $response = $this->putJson(route('api.packs.translations.update', ['pack' => $pack->id, 'translation' => $translation->id]), $translationData);

        // Assert
        $response->assertStatus(200)
            ->assertJsonFragment([
                'from_translation' => strtolower($translationData['from_translation']),
                'to_translation' => strtolower($translationData['to_translation']),
            ]);
        $this->assertDatabaseHas('translations', [
            'from_translation' => $translationData['from_translation'],
            'to_translation' => $translationData['to_translation'],
        ]);
    }

    #[Test]
    #[Group('translation')]
    #[DataProvider('invalidTranslationData')]
    public function user_can_not_update_translation_with_invalid_data($translationData, $errorMessages): void
    {
        // Arrange
        $user = $this->authenticateUser();
        $pack = Pack::factory()->create([
            'user_id' => $user->id,
        ]);
        $translation = Translation::factory()->create([
            'pack_id' => $pack->id,
            'from_translation' => 'Hello',
            'to_translation' => 'Hallo',
        ]);

        // Act
        $response = $this->putJson(route('api.packs.translations.update', ['pack' => $pack->id, 'translation' => $translation->id]), $translationData);

        // Assert
        $response->assertStatus(422)
            ->assertJsonFragment($errorMessages);

        $this->assertDatabaseMissing('translations', [
            'from_translation' => $translationData['from_translation'],
            'to_translation' => $translationData['to_translation'],
        ]);
    }

    #[Test]
    #[Group('translation')]
    public function user_can_not_update_translation_with_existing_data(): void
    {
        // Arrange
        $user = $this->authenticateUser();
        $pack = Pack::factory()->create([
            'user_id' => $user->id,
        ]);
        $translation1 = Translation::factory()->create([
            'pack_id' => $pack->id,
            'from_translation' => 'Hello',
            'to_translation' => 'Holla',
        ]);
        $translation2 = Translation::factory()->create([
            'pack_id' => $pack->id,
            'from_translation' => 'Goodbye',
            'to_translation' => 'Tschüss',
        ]);

        // Act
        $response = $this->putJson(route('api.packs.translations.update', ['pack' => $pack->id, 'translation' => $translation1->id]), [
            'from_translation' => $translation2->from_translation,
            'to_translation' => $translation2->to_translation,
        ]);

        // Assert
        $response->assertStatus(422)
            ->assertJsonFragment([
                'message' => 'Validation failed',
                'pack_id' => [
                    'Translation already exists',
                ],
            ]);
    }

    #[Test]
    #[Group('translation')]
    public function user_can_not_update_translation_with_unauthorized_pack(): void
    {
        // Arrange
        $user = $this->authenticateUser();
        $pack = Pack::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);
        $translation = Translation::factory()->create([
            'pack_id' => $pack->id,
            'from_translation' => 'Hello',
            'to_translation' => 'Hallo',
        ]);

        // Act
        $response = $this->putJson(route('api.packs.translations.update', ['pack' => $pack->id, 'translation' => $translation->id]), [
            'from_translation' => 'Hello',
            'to_translation' => 'Hola',
        ]);

        // Assert
        $response->assertStatus(403);
    }

    #[Test]
    #[Group('translation')]
    public function user_can_delete_translation(): void
    {
        // Arrange
        $user = $this->authenticateUser();
        $pack = Pack::factory()->create([
            'user_id' => $user->id,
        ]);
        $translation = Translation::factory()->create([
            'pack_id' => $pack->id,
            'from_translation' => 'Hello',
            'to_translation' => 'Hallo',
        ]);

        // Act
        $response = $this->deleteJson(route('api.packs.translations.destroy', ['pack' => $pack->id, 'translation' => $translation->id]));

        // Assert
        $response->assertStatus(204);
        $this->assertDatabaseMissing('translations', [
            'from_translation' => 'Hello',
            'to_translation' => 'Hallo',
        ]);
    }

    #[Test]
    #[Group('translation')]
    public function user_can_not_delete_translation_with_unauthorized_pack(): void
    {
        // Arrange
        $user = $this->authenticateUser();
        $pack = Pack::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);
        $translation = Translation::factory()->create([
            'pack_id' => $pack->id,
            'from_translation' => 'Hello',
            'to_translation' => 'Hallo',
        ]);

        // Act
        $response = $this->deleteJson(route('api.packs.translations.destroy', ['pack' => $pack->id, 'translation' => $translation->id]));

        // Assert
        $response->assertStatus(403);
        $this->assertDatabaseHas('translations', [
            'from_translation' => 'Hello',
            'to_translation' => 'Hallo',
        ]);
    }

    public static function validTranslationData(): array
    {
        return [
            [
                [
                    'from_translation' => 'Hello',
                    'to_translation' => 'Hola',
                ]
            ],
            [
                [
                    'from_translation' => 'Goodbye',
                    'to_translation' => 'Adiós',
                ]
            ],
            [
                [
                    'from_translation' => 'Thank you',
                    'to_translation' => 'Gracias',
                ]
            ]
        ];
    }

    public static function invalidTranslationData(): array
    {
        return [
            [
                [
                    'from_translation' => '',
                    'to_translation' => 'Hola',
                ],
                [
                    'from_translation' => ['The from translation field is required.'],
                ],
            ],
            [
                [
                    'from_translation' => 'Hello',
                    'to_translation' => '',
                ],
                [
                    'to_translation' => ['The to translation field is required.'],
                ],
            ],
            [
                [
                    'from_translation' => null,
                    'to_translation' => 'Hola',
                ],
                [
                    'from_translation' => ['The from translation field is required.'],
                ],
            ],
            [
                [
                    'from_translation' => 'Hello',
                    'to_translation' => null,
                ],
                [
                    'to_translation' => ['The to translation field is required.'],
                ],
            ],
            [
                [
                    'from_translation' => 123,
                    'to_translation' => 'Hola',
                ],
                [
                    'from_translation' => ['The from translation field must be a string.'],
                ],
            ],
            [
                [
                    'from_translation' => 'Hello',
                    'to_translation' => 123,
                ],
                [
                    'to_translation' => ['The to translation field must be a string.'],
                ],
            ],
        ];
    }
}
