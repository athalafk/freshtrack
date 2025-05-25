<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'username' => 'admin',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'username' => 'athala',
            'password' => bcrypt('mnpoilk'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'username' => 'helmi',
            'password' => bcrypt('bebasaja123'),
            'role' => 'staf',
        ]);

        User::factory()->create([
            'username' => 'bayu',
            'password' => bcrypt('bebasjuga123'),
            'role' => 'staf',
        ]);

        User::factory()->create([
            'username' => 'hilmi',
            'password' => bcrypt('mikomiko'),
            'role' => 'staf',
        ]);

        User::factory()->create([
            'username' => 'raihan',
            'password' => bcrypt('kenapablokirane'),
            'role' => 'staf',
        ]);

        User::factory()->create([
            'username' => 'ahmad',
            'password' => bcrypt('touring'),
            'role' => 'staf',
        ]);

        User::factory()->create([
            'username' => 'parel',
            'password' => bcrypt('anomali'),
            'role' => 'staf',
        ]);
    }
}
