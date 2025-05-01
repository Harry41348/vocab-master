<?php

namespace Tests\Support;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait TestHelpers
{
    /**
     * Authenticate a user using Sanctum.
     */
    public function authenticateUser(?User $user = null): User
    {
        $user = $user ?? User::factory()->create();
        Sanctum::actingAs($user);

        return $user;
    }
}
