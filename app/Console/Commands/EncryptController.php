<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;

class EncryptController extends Command
{
    protected $signature = 'encrypt:controller';
    protected $description = 'Encrypt the EvidenceController file';

    public function handle()
    {
        $controllerPath = app_path('Http/Controllers/EvidenceController.php');
        
        if (!file_exists($controllerPath)) {
            $this->error('EvidenceController.php not found!');
            return;
        }

        // Read the controller file
        $content = file_get_contents($controllerPath);
        
        // Remove PHP opening tag
        $content = preg_replace('/^<\?php\s+/', '', $content);
        
        // Encrypt the content using Laravel's encryption
        $encrypted = Crypt::encryptString($content);
        
        // Create the encrypted version with a loader
        $encryptedContent = "<?php\n\n";
        $encryptedContent .= "// Encrypted Controller - Do not modify\n";
        $encryptedContent .= "if (!function_exists('decrypt_controller')) {\n";
        $encryptedContent .= "    function decrypt_controller() {\n";
        $encryptedContent .= "        return Illuminate\Support\Facades\Crypt::decryptString('" . $encrypted . "');\n";
        $encryptedContent .= "    }\n";
        $encryptedContent .= "}\n";
        $encryptedContent .= "eval(decrypt_controller());\n";
        
        // Backup the original file
        copy($controllerPath, $controllerPath . '.backup');
        
        // Write the encrypted content
        file_put_contents($controllerPath, $encryptedContent);
        
        $this->info('Controller has been encrypted successfully!');
        $this->info('A backup of the original file has been created at: ' . $controllerPath . '.backup');
    }
} 