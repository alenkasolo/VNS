<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    Use RefreshDatabase;


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function createOneUserFail()
    {
        $data = [
            "id" => "A0000002",
            "user_name" => "Nguyen Van A",
            "email" => "hello@gmail.com",
            "password" => "helloKitty",
            "role_id" => 3,
            "status" => 3
        ];
        $user = User::whereId('A0000001')->first();
        $response = $this->actingAs($user, 'sanctum')
            ->putJson('/users/A0000001', $data);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('id', 'user_res_msg');
        $response->assertJsonValidationErrors('role_id', 'user_res_msg');
    }

    public function createOneSuccess() {
        $data = [

        ];
    }
}
