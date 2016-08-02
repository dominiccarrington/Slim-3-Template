<?php
namespace App\Http;

use App\Contract\HttpContract;

class Cookie implements HttpContract
{
    /**
     * Set a cookie to the client
     * @param string $key
     * @param mixed $value
     * @param string $expiry
     * @return bool If setting the cookie was successful
     */
    public function set($key, $value, $expiry = '1 week')
    {
        return setcookie($key, $value, strtotime($expiry), '/');
    }

    /**
     * Get a set cookie from the client
     * @param  string $key
     * @return mixed
     */
    public function get($key)
    {
        return self::exists($key) ? $_COOKIE[$key] : null;
    }

    /**
     * Check if a cookie exists
     * @param  string $key
     * @return bool
     */
    public function exists($key)
    {
        return (isset($_COOKIE[$key])) ? true : false;
    }

    /**
     * Delete a cookie
     * @param  string $key
     * @return void
     */
    public function delete($key)
    {
        self::set($key, '', time() - 1);
    }

    /**
     * Flush the cookies set
     * @return void
     */
    public function flush()
    {
        foreach ($_COOKIE as $key => $value) {
            self::set($key, '', time() - 1);
        }
    }
}
