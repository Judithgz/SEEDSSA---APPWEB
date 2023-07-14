<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Judith Gonzalez',
            'email' => 'judithnarvaez27@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('Tumblr19*'), // password
            'phone' => '4772068605',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Paciente 1',
            'email' => 'paciente@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'), // password
            'role' => 'paciente',
        ]);

        User::create([
            'name' => 'Judith Gonzalez',
            'email' => 'medico1@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678*'), // password
            'role' => 'colaboradores',
        ]);

        User::factory()
            ->count(50)
            ->state(['role' => 'paciente'])
            ->create();
    }
}