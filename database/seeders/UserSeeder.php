<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $SMRole = Role::whereName('System Manager')->first();
        $GURole = Role::whereName('General User')->first();
        User::insertOrIgnore([
            [
                'id' => 'A0000001',
                'user_name' => 'La Hong Hai',
                'email' => 'alenkasolo1@gmail.com',
                'password' => Str::random(25),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'role_id' => $SMRole->id,
                'status' => 1
            ],
            [
                'id' => 'A0000002',
                'user_name' => 'Nguyen Van A',
                'email' => 'alenkasolo2@gmail.com',
                'password' => Str::random(25),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'role_id' => $GURole->id,
                'status' => 1
            ]
        ]);
    }
}
