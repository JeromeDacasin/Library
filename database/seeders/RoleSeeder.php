<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' =>  'Librarian',
                'description' => 'Librarian'
            ],
            [
                'name' =>  'Student',
                'description' => 'Student'
            ],
            [
                'name' =>  'Teacher',
                'description' => 'Teacher'
            ],
            [
                'name' =>  'Faculty',
                'description' => 'Faculty'
            ]
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate($role); 
        }
        
    }
}
