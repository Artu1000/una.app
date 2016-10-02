<?php

namespace App\Helpers;

class EnvHelper
{
    /**
     * @param $env_key
     * @param $value
     */
    public function createOrReplace($env_key, $value)
    {
        // we get the env file path
        $path = base_path('.env');
        
        // if the env file exists
        if (file_exists($path)) {
            // we get the env file content
            $env_content = file_get_contents($path);
            // if the key already exists
            if (strpos($env_content, $env_key) !== false) {
                // we replace its value by the new one
                file_put_contents(
                    $path,
                    str_replace(
                        $env_key . '=' . env($env_key),
                        $env_key . '=' . $value,
                        file_get_contents($path)
                    )
                );
            } else {
                // if the key does not exists, we create a new one
                $content = <<<EOT
$env_content
$env_key=$value
EOT;
                file_put_contents($path, $content);
            }
        }
    }
    
}