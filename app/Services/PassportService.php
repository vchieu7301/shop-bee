<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class PassportService
{
    public function authenticate($username, $password)
    {
        $response = Http::asForm()->post(config('services.passport.login_endpoint'), [
            'grant_type' => 'password',
            'client_id' => Crypt::decrypt(config('services.passport.client_id')),
            'client_secret' => Crypt::decrypt(config('services.passport.client_secret')),
            'username' => $username,
            'password' => $password,
            'scope' => '',
        ]);

        return $response->json();
    }
}
