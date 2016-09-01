<?php
/**
 * Shorten class names in this array in order to easy access classes.
 */

return [
    /**
     * Vender Shortcuts
     */
    "Eloquent" => Illuminate\Database\Eloquent\Model::class,
    "DB" => Illuminate\Database\Capsule\Manager::class,
    "FileSystem" => Illuminate\Filesystem\Filesystem::class,

    /**
     * App Shortcuts
     */
    "Session" => App\Http\Session::class,
    "Cookie" => App\Http\Cookie::class,
    "Hash" => App\Auth\Hash::class,
    "Auth" => App\Auth\Auth::class,

    // Edit below here:
    
];
