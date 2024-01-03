<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_it_returns_users()
    {
        $users = \App\Models\User::factory(5)->create();

        $response = $this->getJson(route('users.index'));

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure(['data' => [['id', 'name', 'email']]]);
    }

    public function test_it_creates_a_user()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password,
        ];

        $response = $this->postJson(route('users.store'), $userData);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'name', 'email']]);

        $this->assertDatabaseHas('users', ['email' => $userData['email']]);
    }

    public function test_it_shows_a_user()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->getJson(route('users.show', ['user' => $user->id]));

        $response->assertStatus(200)
            ->assertJson(['data' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email]]);
    }

    public function test_it_updates_a_user()
    {
        $user = \App\Models\User::factory()->create();
        $newUserData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password,
        ];

        $response = $this->putJson(route('users.update', ['user' => $user->id]), $newUserData);

        $response->assertStatus(200)
            ->assertJson(['data' => ['id' => $user->id, 'name' => $newUserData['name'], 'email' => $newUserData['email']]]);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'email' => $newUserData['email']]);
    }

    public function test_it_deletes_a_user()
    {
        $user = \App\Models\User::factory()->create();
        ;

        $response = $this->deleteJson(route('users.destroy', ['user' => $user->id]));

        $response->assertStatus(200)
            ->assertJson(['data' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email]]);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
