<?php
namespace App\Auth;

use Exception;
use InvalidArgumentException;
use RuntimeException;
use Session;

class Auth
{
    public static function check($values = [])
    {
        if (self::loggedIn()) {
			return true;
		}
        
        $model = get_model("user");

		if (!isset($values["password"])) {
			throw new Exception("Passsword was not defined in values");
		}

		$password = $values["password"];
		unset($values["password"]);

		if ($model === false) {
			throw new RuntimeException("User model is not defined.");
		}

		if (!class_exists($model)) {
			throw new InvalidArgumentException("Class " . $model . " does not exist");
		}

		if (!in_array("Illuminate\Contracts\Auth\Authenticatable", class_implements($model))) {
			throw new InvalidArgumentException("Class " .
				$model .
				" must implement Illuminate\Contracts\Auth\Authenticatable");
		}

		$user = null;
		foreach ($values as $col => $value) {
			$found = $model::where($col, $value);
			if ($found->exists()) {
				$user = $found->first();
				break;
			}
		}
		
		if (is_null($user) || !Hash::verify($password, $user->getAuthPassword())) {
			return false;
		}

		Session::set("user", $user->getAuthIdentifier());
		return true;
    }

    public static function user()
    {
        $model = get_model("user");
        return $model::where($model::getAuthIdentifierName(), Session::get("user"))->firstOrFail();
    }

    public static function loggedIn()
    {
        return Session::exists("user");
    }
}
