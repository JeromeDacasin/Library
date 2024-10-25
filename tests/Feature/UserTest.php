<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_index()
    {
        $response = $this->get('/api/users');

        $response->assertStatus(200);

    }

    public function test_user_login()
    {
        $response = $this->post('/api/login');

        $response->assertStatus(200);
    }
}
