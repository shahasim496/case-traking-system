<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Crypt;

class EncryptedControllerServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register the decrypt_controller function globally
        if (!function_exists('decrypt_controller')) {
            function decrypt_controller($encrypted) {
                return Crypt::decryptString($encrypted);
            }
        }
    }

    public function boot()
    {
        //
    }
} 