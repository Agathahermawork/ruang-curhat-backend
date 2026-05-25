<?php

namespace Tests\Feature;

use Tests\TestCase;

class TestingApiTest extends TestCase
{
    public function test_health_endpoint_returns_ok_response(): void
    {
        $this->getJson('/api/testing/health')
            ->assertOk()
            ->assertJson([
                'status' => 'ok',
                'message' => 'API is reachable.',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'timestamp',
            ]);
    }

    public function test_runtime_endpoint_returns_safe_runtime_information(): void
    {
        $this->getJson('/api/testing/runtime')
            ->assertOk()
            ->assertJsonStructure([
                'app',
                'environment',
                'debug',
                'timezone',
                'php_version',
                'laravel_version',
            ]);
    }
}
