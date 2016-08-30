<?php
namespace App\Model;

use Eloquent;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable;

class User extends Eloquent implements 
    Authenticatable,
    Authorizable,
    CanResetPassword
{
    public function getAuthIdentifierName()
    {
        return "id";
    }

    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->{$this->getRememberTokenName()};
    }

    public function setRememberToken($value)
    {
        $this->{$this->getRememberTokenName()} = $value;
        $this->save();
    }

    public function getRememberTokenName()
    {
        return "remember_token";
    }

    public function can($ability, $arguments = [])
    {
        return true;
    }

    public function getEmailForPasswordReset()
    {
        return $this->email;
    }
}
