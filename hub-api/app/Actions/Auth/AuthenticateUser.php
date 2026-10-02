<?php

namespace App\Actions\Auth;

use App\Http\Requests\Auth\LoginRequest;

class AuthenticateUser
{
    public function handle(LoginRequest $request): void
    {
        $request->authenticate();
    }
}