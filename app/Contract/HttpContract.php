<?php
namespace App\Contract;

interface HttpContract
{
    public function set($key, $value, $expires);

    public function get($key);

    public function exists($key);

    public function delete($key);

    public function flush();
}
