<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class OauthClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('oauth_clients')->insert([
            'id' => 1,
            'name' => 'Password Grant Client',
            'secret' => 'fP07LLJKCibZoLNmxiak7YRgweWTqv7IluA0j8Qs',
            'provider' => 'users',
            'redirect' => 'http://localhost',
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
