<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
class UserControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testUserIsCreatedSuccessfully()
    {

        $payload = [
            'name' => $this->faker->userName,
            'email'  => $this->faker->email,
            'password'    => '123456789',
         
        ];
        
        $this->json('post', 'api/auth/register', $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'user' => [
                        'id',
                        'name',
                        'email',
                     

                    ]
                ]
            );
        $check = array_filter($payload, fn ($array) => in_array($array, ['name', 'email']));
        $this->assertDatabaseHas('users', $check);
    }
    public function testUserIsLoggedInSuccessfully()
    {
        $user = User::factory()->create();

        $payload = [
            'email' => $user->email,
            'password' => 'password', 
        ];
        
        $this->json('post', 'api/auth/login', $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['token']);

        $this->assertAuthenticated();
    }
    public function testUserIsLoggedOutSuccessfully()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->json('post', 'api/auth/logout')
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(['status' => true, 'message' => 'User logged out successfully']);

        $this->assertGuest();
    }
}