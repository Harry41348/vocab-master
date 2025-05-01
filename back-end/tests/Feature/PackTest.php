<?php

namespace Tests\Feature;

use App\Models\Pack;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class PackTest extends TestCase
{
    use TestHelpers;

    #[Test]
    #[Group('pack')]
    public function guest_can_view_pack_index(): void
    {
        // Arrange
        $packs = Pack::factory()->count(5)->create();

        // Act
        $response = $this->get(route('api.packs.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
        $packs->each(function ($pack) use ($response) {
            $response->assertJsonFragment(['name' => $pack->name]);
            $response->assertJsonFragment(['language_from' => $pack->language_from]);
            $response->assertJsonFragment(['language_to' => $pack->language_to]);
        });
    }

    #[Test]
    #[Group('pack')]
    public function user_can_view_pack_index(): void
    {
        // Arrange
        $this->authenticateUser();
        $packs = Pack::factory()->count(5)->create();

        // Act
        $response = $this->get(route('api.packs.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
        $packs->each(function ($pack) use ($response) {
            $response->assertJsonFragment(['name' => $pack->name]);
            $response->assertJsonFragment(['language_from' => $pack->language_from]);
            $response->assertJsonFragment(['language_to' => $pack->language_to]);
        });
    }

    #[Test]
    #[Group('pack')]
    public function guest_can_view_pack_show(): void
    {
        // Arrange
        $pack = Pack::factory()->create();

        // Act
        $response = $this->get(route('api.packs.show', ['pack' => $pack->id]));

        // Assert
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => $pack->name]);
        $response->assertJsonFragment(['language_from' => $pack->language_from]);
        $response->assertJsonFragment(['language_to' => $pack->language_to]);
    }

    #[Test]
    #[Group('pack')]
    public function user_can_view_pack_show(): void
    {
        // Arrange
        $this->authenticateUser();
        $pack = Pack::factory()->create();

        // Act
        $response = $this->get(route('api.packs.show', ['pack' => $pack->id]));

        // Assert
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => $pack->name]);
        $response->assertJsonFragment(['language_from' => $pack->language_from]);
        $response->assertJsonFragment(['language_to' => $pack->language_to]);
    }

    #[Test]
    #[Group('pack')]
    #[DataProvider('valid_data')]
    public function user_can_create_pack_with_valid_data($data): void
    {
        // Arrange
        $user = $this->authenticateUser();

        // Act
        $response = $this->post(route('api.packs.store'), $data);

        // Assert
        $response->assertStatus(201);
        $this->assertDatabaseHas('packs', [
            ...$data,
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    #[Group('pack')]
    #[DataProvider('invalid_data')]
    public function user_cannot_create_pack_with_invalid_data($data, $errors): void
    {
        // Arrange
        $this->authenticateUser();

        // Act
        $response = $this->post(route('api.packs.store'), $data);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonFragment($errors);
        $this->assertDatabaseMissing('packs', [
            ...$data,
        ]);
    }

    #[Test]
    #[Group('pack')]
    public function user_can_not_create_pack_with_existing_name(): void
    {
        // Arrange
        $this->authenticateUser();
        $pack = Pack::factory()->create();

        // Act
        $response = $this->post(route('api.packs.store'), [
            'name' => $pack->name,
            'language_from' => 'fr',
            'language_to' => 'de',
        ]);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonFragment([
            'name' => ['The name has already been taken.'],
        ]);
        $this->assertDatabaseHas('packs', [
            'name' => $pack->name,
            'language_from' => $pack->language_from,
            'language_to' => $pack->language_to,
        ]);
    }

    #[Test]
    #[Group('pack')]
    public function guest_cannot_create_pack(): void
    {
        // Act
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('api.packs.store'), [
            'name' => 'Dutch',
            'language_from' => 'en',
            'language_to' => 'nl',
        ]);

        // Assert
        $response->assertStatus(401);
        $this->assertDatabaseMissing('packs', [
            'name' => 'Dutch',
            'language_from' => 'en',
            'language_to' => 'nl    ',
        ]);
    }

    #[Test]
    #[Group('pack')]
    #[DataProvider('valid_data')]
    public function user_can_update_pack_with_valid_data($data): void
    {
        // Arrange
        $user = $this->authenticateUser();
        $pack = Pack::factory()->create([
            'user_id' => $user->id,
        ]);

        // Act
        $response = $this->put(route('api.packs.update', ['pack' => $pack->id]), $data);

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseHas('packs', [
            'id' => $pack->id,
            ...$data,
        ]);
    }

    #[Test]
    #[Group('pack')]
    public function user_cannot_update_another_users_pack(): void
    {
        // Arrange
        $this->authenticateUser();
        $pack = Pack::factory()->create();

        // Act
        $response = $this->put(route('api.packs.update', ['pack' => $pack->id]), [
            'name' => 'Updated Pack',
            'language_from' => 'fr',
            'language_to' => 'de',
        ]);

        // Assert
        $response->assertStatus(403);
        $this->assertDatabaseMissing('packs', [
            'id' => $pack->id,
            'name' => 'Updated Pack',
            'language_from' => 'fr',
            'language_to' => 'de',
        ]);
    }

    #[Test]
    #[Group('pack')]
    #[DataProvider('invalid_data')]
    public function user_cannot_update_pack_with_invalid_data($data, $errors): void
    {
        // Arrange
        $this->authenticateUser();
        $pack = Pack::factory()->create();

        // Act
        $response = $this->put(route('api.packs.update', ['pack' => $pack->id]), $data);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonFragment($errors);
        $this->assertDatabaseMissing('packs', [
            'id' => $pack->id,
            ...$data,
        ]);
    }

    #[Test]
    #[Group('pack')]
    public function user_can_not_update_non_existent_pack(): void
    {
        // Arrange
        $this->authenticateUser();

        // Act
        $response = $this->put(route('api.packs.update', ['pack' => 999]), [
            'name' => 'Updated Pack',
            'language_from' => 'fr',
            'language_to' => 'de',
        ]);

        // Assert
        $response->assertStatus(404);
    }

    #[Test]
    #[Group('pack')]
    public function user_can_delete_pack(): void
    {
        // Arrange
        $user = $this->authenticateUser();
        $pack = Pack::factory()->create([
            'user_id' => $user->id,
        ]);

        // Act
        $response = $this->delete(route('api.packs.destroy', ['pack' => $pack->id]));

        // Assert
        $response->assertStatus(204);
        $this->assertDatabaseMissing('packs', [
            'id' => $pack->id,
        ]);
    }

    #[Test]
    #[Group('pack')]
    public function user_cannot_delete_another_users_pack(): void
    {
        // Arrange
        $this->authenticateUser();
        $pack = Pack::factory()->create();

        // Act
        $response = $this->delete(route('api.packs.destroy', ['pack' => $pack->id]));

        // Assert
        $response->assertStatus(403);
        $this->assertDatabaseHas('packs', [
            'id' => $pack->id,
        ]);
    }

    #[Test]
    #[Group('pack')]
    public function guest_cannot_delete_pack(): void
    {
        // Arrange
        $pack = Pack::factory()->create();

        // Act
        $response = $this->withHeader('Accept', 'application/json')->delete(route('api.packs.destroy', ['pack' => $pack->id]));

        // Assert
        $response->assertStatus(401);
        $this->assertDatabaseHas('packs', [
            'id' => $pack->id,
        ]);
    }

    #[Test]
    #[Group('pack')]
    public function user_can_not_delete_non_existent_pack(): void
    {
        // Arrange
        $this->authenticateUser();

        // Act
        $response = $this->delete(route('api.packs.destroy', ['pack' => 999]));

        // Assert
        $response->assertStatus(404);
    }

    public static function valid_data(): array
    {
        return [
            [[
                'name' => 'Test Pack',
                'description' => 'This is a test pack.',
                'language_from' => 'en',
                'language_to' => 'es',
            ]],
            [[
                'name' => 'This is my really long pack name',
                'description' => 'This is a test pack.',
                'language_from' => 'gb',
                'language_to' => 'jp',
            ]],
            [[
                'name' => 'Test Pack',
                'language_from' => 'en',
                'language_to' => 'es',
            ]],
        ];
    }

    public static function invalid_data(): array
    {
        return [
            [
                [
                    'name' => '',
                    'language_from' => 'en',
                    'language_to' => 'es',
                ],
                [
                    'name' => ['The name field is required.'],
                ],
            ],
            [
                [
                    'name' => 123,
                    'language_from' => 'en',
                    'language_to' => 'es',
                ],
                [
                    'name' => ['The name field must be a string.'],
                ],
            ],
            [
                [
                    'name' => 'Dutch',
                    'description' => 123,
                    'language_from' => 'en',
                    'language_to' => 'es',
                ],
                [
                    'description' => ['The description field must be a string.'],
                ],
            ],
            [
                [
                    'name' => 'Test Pack',
                    'language_from' => '',
                    'language_to' => 'es',
                ],
                [
                    'language_from' => ['The language from field is required.'],
                ],
            ],
            [
                [
                    'name' => 'Test Pack',
                    'language_from' => 'en',
                    'language_to' => '',
                ],
                [
                    'language_to' => ['The language to field is required.'],
                ],
            ],
        ];
    }
}
