<?php

namespace Database\Seeders;

use App\Models\Counselor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Agatha Herma (PM)',
                'password' => 'admin123',
                'pangkat' => 'KAPTEN (SUS)',
                'nrp' => '5410291',
                'jabatan' => 'Kasi Bintalud',
                'kesatuan' => 'Lanud Abdulrachman Saleh',
                'telegram' => '+6281234567890',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'Anonim Damar',
                'password' => 'user123',
                'pangkat' => 'LETDA (SUS)',
                'nrp' => '5430221',
                'jabatan' => 'Prajurit Dinas',
                'kesatuan' => 'Lanud Adisutjipto',
                'telegram' => '+628987654321',
                'role' => 'user',
            ]
        );

        collect([
            [
                'name' => 'Ustadz Drs. H. Ahmad Fauzi, M.Ag.',
                'pangkat' => 'MAYOR (SUS)',
                'nrp' => '5241029',
                'jabatan' => 'Kasi Bintal Islam',
                'kesatuan' => 'Mabesau',
                'telegram' => '+628123456789',
                'religion' => 'Islam',
                'emoji' => '🕌',
            ],
            [
                'name' => 'Pendeta Yoseph Siregar, S.Th.',
                'pangkat' => 'KAPTEN (SUS)',
                'nrp' => '5321045',
                'jabatan' => 'Kasi Bintal Kristen',
                'kesatuan' => 'Lanud Abdulrachman Saleh',
                'telegram' => 'yosephsiregar',
                'religion' => 'Kristen',
                'emoji' => '✝️',
            ],
            [
                'name' => 'Romo FX. Fransiskanus, Pr.',
                'pangkat' => 'KAPTEN (SUS)',
                'nrp' => '5432091',
                'jabatan' => 'Pati Rohani Katolik',
                'kesatuan' => 'Lanud Iswahjudi',
                'telegram' => 'romo_frans',
                'religion' => 'Katolik',
                'emoji' => '⛪',
            ],
        ])->each(fn (array $counselor) => Counselor::updateOrCreate(
            ['nrp' => $counselor['nrp']],
            $counselor
        ));
    }
}
