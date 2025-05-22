<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Crypt;

if (!function_exists('decrypt_controller')) {
    function decrypt_controller($encrypted) {
        return Crypt::decryptString($encrypted);
    }
}

class EncryptedControllerServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Function is now registered globally
    }

    public function boot()
    {
        //
    }
} 