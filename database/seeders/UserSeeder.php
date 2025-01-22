<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::all();

        foreach($roles as $role) {
           $users =  User::factory()
            ->count(30)
            ->create(['role_id' => $role->id]);

            foreach ($users as $user) {
                UserInformation::factory()->create([
                    'user_id' => $user->id,
                    'student_number' => $role->name === 'Student' 
                        ? fake()->numerify('#######') 
                        : null,
                ]);
            }
        }
    }
}
