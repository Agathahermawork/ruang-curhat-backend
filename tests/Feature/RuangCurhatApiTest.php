<?php

namespace Tests\Feature;

use App\Models\Counselor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Tests\TestCase;

#[RequiresPhpExtension('pdo_sqlite')]
class RuangCurhatApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_fetch_profile(): void
    {
        User::create([
            'name' => 'Admin Ruang Curhat',
            'email' => 'admin@example.com',
            'password' => 'secret123',
            'pangkat' => 'KAPTEN (SUS)',
            'nrp' => '5410291',
            'jabatan' => 'Kasi Bintalud',
            'kesatuan' => 'Lanud Abdulrachman Saleh',
            'telegram' => '+6281234567890',
            'role' => 'admin',
        ]);

        $login = $this->postJson('/api/auth/login', [
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ]);

        $login->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Autentikasi berhasil.',
                'token_type' => 'Bearer',
            ])
            ->assertJsonPath('data.role', 'admin')
            ->assertJsonStructure(['token', 'data' => ['name', 'email', 'pangkat', 'nrp']]);

        $this->withToken($login->json('token'))
            ->getJson('/api/auth/me')
            ->assertOk()
            ->assertJsonPath('data.email', 'admin@example.com');
    }

    public function test_counselors_can_be_filtered_by_religion(): void
    {
        Counselor::create([
            'name' => 'Konselor Islam',
            'pangkat' => 'MAYOR (SUS)',
            'nrp' => '5241029',
            'jabatan' => 'Kasi Bintal Islam',
            'kesatuan' => 'Mabesau',
            'telegram' => 'ustadz_ahmad',
            'religion' => 'Islam',
            'emoji' => '🕌',
        ]);

        Counselor::create([
            'name' => 'Konselor Katolik',
            'pangkat' => 'KAPTEN (SUS)',
            'nrp' => '5432091',
            'jabatan' => 'Pati Rohani Katolik',
            'kesatuan' => 'Lanud Iswahjudi',
            'telegram' => 'romo_frans',
            'religion' => 'Katolik',
            'emoji' => '⛪',
        ]);

        $this->getJson('/api/counselors?religion=Islam')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Konselor Islam')
            ->assertJsonPath('data.0.religion', 'Islam');
    }

    public function test_counselor_can_be_created(): void
    {
        $this->postJson('/api/counselors', [
            'name' => 'Pembimbing Hindu',
            'pangkat' => 'kapten (sus)',
            'nrp' => '5550001',
            'jabatan' => 'Pembimbing Rohani',
            'kesatuan' => 'Lanud Abdulrachman Saleh',
            'telegram' => 'pembimbing_hindu',
            'religion' => 'Hindu',
        ])
            ->assertCreated()
            ->assertJsonPath('data.pangkat', 'KAPTEN (SUS)')
            ->assertJsonPath('data.religion', 'Hindu');

        $this->assertDatabaseHas('counselors', [
            'nrp' => '5550001',
            'religion' => 'Hindu',
        ]);
    }
}
