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

    /**
     * App Shortcuts
     */
    "Session" => App\Http\Session::class,
    "Cookie" => App\Http\Cookie::class,
    "FileSystem" => App\File\FileSystem::class,
    "Hash" => App\Auth\Hash::class,
];
