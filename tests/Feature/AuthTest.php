<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;


class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $clientRepository;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate', ['-vvv' => true]);
        Artisan::call('passport:install', ['-vvv' => true]);
        Artisan::call('db:seed', ['-vvv' => true]);
    }

    /**
     * Test user registration.
     *
     * @return void
     */
    public function testUserRegistration()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(201);
    }

    /**
     * Test user login.
     *
     * @return void
     */
    public function testUserLogin()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->json('POST', '/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    /**
     * Test user logout.
     *
     * @return void
     */
    public function testUserLogout()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->json('POST', '/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Successfully logged out']);
    }
}