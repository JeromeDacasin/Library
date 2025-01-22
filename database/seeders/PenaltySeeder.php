<?php

namespace Database\Seeders;

use App\Models\Penalty;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenaltySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::all();
        $startFine = 5;

        foreach($roles as  $key => $value) {
            info($key);
            Penalty::updateOrCreate([
                'fine'    => $startFine * ($key + 1),
                'role_id' => $value->id
            ]);
        } 
    }
}
