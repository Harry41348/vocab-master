<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    #[Test]
    #[Group('register')]
    public function user_can_register_with_valid_details(): void
    {
        // Arrange
        $registerDetails = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
        ];

        // Act
        $response = $this->post(route('api.register'), $registerDetails);
        $user = User::where('email', 'john.doe@example.com')->first();

        // Assert
        $response
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'user' => [
                        'name' => 'John Doe',
                        'email' => 'john.doe@example.com',
                    ],
                ],
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user',
                    'access_token',
                ],
            ])
            ->assertJsonMissing([
                'password' => 'password',
            ]);
        $this->assertTrue(Hash::check('password', $user->password));
    }

    #[Test]
    #[Group('register')]
    public function user_can_not_register_with_same_email(): void
    {
        // Arrange
        $registerDetails = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
        ];
        User::create($registerDetails);
        $count = User::count();

        // Act
        $response = $this->post(route('api.register'), $registerDetails);

        // Assert
        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Validation failed',
                'errors' => [
                    'email' => ['The email has already been taken.'],
                ],
            ]);
        $this->assertTrue(User::count() == $count);
    }

    #[Test]
    #[Group('register')]
    #[DataProvider('invalid_register_attributes')]
    public function user_can_not_register_without_valid_attributes($registerDetails, $errors): void
    {
        // Act
        $response = $this->post(route('api.register'), $registerDetails);

        // Assert
        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Validation failed',
                'errors' => $errors,
            ]);
    }

    public static function invalid_register_attributes(): array
    {
        return [
            [
                [
                    'name' => '',
                    'email' => 'john.doe@email.com',
                    'password' => 'password',
                ],
                [
                    'name' => ['The name field is required.'],
                ],
            ],
            [
                [
                    'name' => 'John Doe',
                    'email' => 'john.doe',
                    'password' => 'password',
                ],
                [
                    'email' => ['The email field must be a valid email address.'],
                ],
            ],
            [
                [
                    'name' => 'John Doe',
                    'email' => '',
                    'password' => 'password',
                ],
                [
                    'email' => ['The email field is required.'],
                ],
            ],
            [
                [
                    'name' => 'John Doe',
                    'email' => 'john.doe@email.com',
                    'password' => '',
                ],
                [
                    'password' => ['The password field is required.'],
                ],
            ],
            [
                [
                    'name' => 'John Doe',
                    'email' => 'john.doe@email.com',
                    'password' => 'pass',
                ],
                [
                    'password' => ['The password field must be at least 8 characters.'],
                ],
            ],
            [
                [
                    'name' => 123,
                    'email' => 123,
                    'password' => 'password',
                ],
                [
                    'email' => ['The email field must be a string.', 'The email field must be a valid email address.'],
                    'name' => ['The name field must be a string.'],
                ],
            ],
        ];
    }

    #[Test]
    #[Group('register')]
    #[Group('login')]
    public function user_can_register_and_login(): void
    {
        // Arrange
        $registerDetails = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
        ];

        // Act - Register
        $this->post(route('api.register'), $registerDetails);

        // Assert - Register
        $this->assertTrue(User::count() == 1);

        // Act - Login
        $response = $this->post(
            route('api.login'),
            [
                'email' => 'john.doe@example.com',
                'password' => 'password',
            ]
        );

        // Assert - Login
        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'access_token',
                ],
            ]);
    }

    #[Test]
    #[Group('login')]
    public function user_can_login(): void
    {
        // Arrange
        User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
        ]);

        // Act
        $response = $this->post(
            route('api.login'),
            [
                'email' => 'john.doe@example.com',
                'password' => 'password',
            ]
        );

        // Assert
        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'access_token',
                ],
            ]);
    }

    #[Test]
    #[Group('login')]
    #[DataProvider('invalid_login_attributes')]
    public function user_can_not_login_with_invalid_details($loginDetails, $statusCode, $jsonResponse): void
    {
        // Arrange
        User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
        ]);

        // Act
        $response = $this->post(route('api.login'), $loginDetails);

        // Assert
        $response
            ->assertStatus($statusCode)
            ->assertJson($jsonResponse);
    }

    public static function invalid_login_attributes(): array
    {
        $invalidCredentials = [
            'success' => false,
            'data' => null,
            'message' => 'Invalid credentials',
        ];

        return [
            [
                [
                    'email' => 'john@example.com',
                    'password' => 'password',
                ],
                401,
                $invalidCredentials,
            ],
            [
                [
                    'email' => 'john.doe@example.com',
                    'password' => 'password123',
                ],
                401,
                $invalidCredentials,
            ],
            [
                [
                    'email' => 'john.doe@example.com',
                    'password' => 'Password',
                ],
                401,
                $invalidCredentials,
            ],
            [
                [
                    'email' => '',
                    'password' => 'password',
                ],
                422,
                [
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => [
                        'email' => [
                            'The email field is required.',
                        ],
                    ],
                ],
            ],
            [
                [
                    'email' => 'john.doe@example.com',
                    'password' => '',
                ],
                422,
                [
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => [
                        'password' => [
                            'The password field is required.',
                        ],
                    ],
                ],
            ],
        ];
    }

    #[Test]
    #[Group('login')]
    public function user_can_not_login_when_they_do_not_exist(): void
    {
        // Act
        $response = $this->post(route('api.login'), [
            'email' => 'john.doe@example.com',
            'password' => 'password',
        ]);

        // Assert
        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'data' => null,
                'message' => 'Invalid credentials',
            ]);
    }

    #[Test]
    #[Group('logout')]
    public function user_can_logout(): void
    {
        // Arrange
        Sanctum::actingAs(
            User::factory()->create()
        );

        // Act
        $response = $this->post(route('api.logout'));

        // Assert
        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => 'Successfully logged out.',
            ]);
    }

    #[Test]
    #[Group('logout')]
    public function guest_can_not_logout(): void
    {
        // Act
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('api.logout'));

        // Assert
        $response
            ->assertStatus(401);
    }
}
