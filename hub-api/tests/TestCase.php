<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    public User $user;
    
    public function setUp(): void 
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }
}
