<?php
if (!function_exists('get_model')) {
    function get_model($key)
    {
        if (!file_exists(CONFIG_DIR . "/models.php")) {
            throw new \RuntimeException("Models file does not exist in config directory.");
        }
        $models = require CONFIG_DIR . "/models.php";

        return isset($models[$key]) ? $models[$key] : false;
    }
}
