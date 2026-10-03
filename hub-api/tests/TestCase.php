<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    public User $user;
    
    public function setUp(): void 
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }
}
