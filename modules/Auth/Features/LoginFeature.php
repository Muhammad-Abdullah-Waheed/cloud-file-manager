<?php

namespace Modules\Auth\Features;

use Illuminate\Support\Facades\Auth;

class LoginFeature
{
    public function handle(array $data): bool
    {
        return Auth::attempt(
            [
                'email' => $data['email'],
                'password' => $data['password'],
            ],
            $data['remember'] ?? false
        );
    }
}
