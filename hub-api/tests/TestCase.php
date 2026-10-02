<?php

namespace Tests;

use App\Models\User;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public User $user;
    
    public function setUp(): void 
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }
}
