<?php

namespace Database\Seeders;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['System Manager', 'HR Manager', 'Manager', 'Super Manager', 'General User'];
        foreach ($names as $name) {
            Role::insertOrIgnore([
                'name' => $name,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
        ]);

        }
    }
}
