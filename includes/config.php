<?php
// includes/config.php

function get_env($key, $default = null) {
    // __DIR__ ensures it looks in the same folder as this file
    // Adjust "../.env" if your .env is in the root and config is in /includes/
    $path = __DIR__ . '/../.env'; 
    
    static $env = null;
    if ($env === null && file_exists($path)) {
        $env = parse_ini_file($path);
    }
    
    return ($env && isset($env[$key])) ? $env[$key] : $default;
}
?>