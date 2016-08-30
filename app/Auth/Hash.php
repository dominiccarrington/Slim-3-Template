<?php
namespace App\Auth;

class Hash
{
    public static function create($value)
    {
        return password_hash($value, PASSWORD_BCRYPT);
    }

    public static function verify($hashedValue, $realValue)
    {
        return password_verify($realValue, $hashedValue);
    }

    public static function unique()
    {
        return md5(uniqid());
    }
}
