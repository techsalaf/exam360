<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        $faker = Faker::create();


        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $studentRole = Role::firstOrCreate(['name' => 'Student', 'guard_name' => 'web']);


        $baseUsers = [
            [
                'data' => [
                    'name' => 'Super Admin',
                    'email' => 'admin@ziexam.com',
                    'password' => Hash::make('password'),
                    'is_banned' => false,
                    'email_verified_at' => now(),
                ],
                'role' => 'Super Admin'
            ],
            [
                'data' => [
                    'name' => 'Student One',
                    'email' => 'user@test.com',
                    'password' => Hash::make('password'),
                    'is_banned' => false,
                    'email_verified_at' => now(),
                ],
                'role' => 'Student'
            ]
        ];

        foreach ($baseUsers as $userData) {

            $user = User::firstOrCreate(
                ['email' => $userData['data']['email']], 
                $userData['data']
            );

            if (!$user->hasRole($userData['role'])) {
                $user->assignRole($userData['role']);
            }
        }

        for ($i = 0; $i < 30; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'), 
                'mobile' => $faker->phoneNumber,
                'country' => $faker->country,
                'city' => $faker->city,
                'state' => $faker->state,
                'zip' => $faker->postcode,
                'address' => $faker->streetAddress,
                'is_banned' => $faker->boolean(10),
                'email_verified_at' => $faker->boolean(80) ? now() : null,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);

            $user->assignRole('Student');
        }
    }
}