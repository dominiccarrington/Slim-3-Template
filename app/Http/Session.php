<?php
namespace App\Http;

use App\Contract\HttpContract;

class Session implements HttpContract
{
    /**
     * Set a variable in session
     * @param string $key
     * @param mixed $value
     * @param int|null $expires Only for inheritance
     * @return string The value set into session
     */
    public function set($key, $value, $expires = null)
    {
        return $_SESSION[$key] = $value;
    }

    /**
     * Get a variable which is stored in session
     * @param  string $key
     * @return mixed|null
     */
    public function get($key)
    {
        return self::exists($key) ? $_SESSION[$key] : null;
    }

    /**
     * Checks if a key exists in the session and is not empty
     * @param  string $key
     * @return bool
     */
    public function exists($key)
    {
        return isset($_SESSION[$key]) && !empty($_SESSION[$key]);
    }

    /**
     * Delete the key stored in session
     * @param  string $key
     * @return void
     */
    public function delete($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Remove all session variables
     * @return void
     */
    public function flush()
    {
        $_SESSION = [];
    }
}
