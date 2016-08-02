<?php
namespace App\Contract;

interface HttpContract
{
    function set($key, $value, $expires);

    function get($key);

    function exists($key);

    function delete($key);

    function flush();
}
