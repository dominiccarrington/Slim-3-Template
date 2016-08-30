<?php
namespace App\Auth;

class Hash
{
    public function create($value)
    {
        return password_hash($value, PASSWORD_BCRYPT);
    }

    public function verify($hashedValue, $realValue)
    {
        return password_verify($realValue, $hashedValue);
    }

    public function unique()
    {
        return md5(uniqid());
    }
}
