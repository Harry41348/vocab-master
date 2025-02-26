<?php

namespace Tests\Feature;

use Tests\TestCase;

class MigrationResetTest extends TestCase
{
    /**
     * Test that migrations can be reset
     */
    public function test_migrations_can_be_reset(): void
    {
        // Run the reset migration command
        $this->artisan('migrate:reset')
            ->assertExitCode(0);
    }
}
