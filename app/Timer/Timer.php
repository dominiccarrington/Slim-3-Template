<?php
namespace App\Timer;

class Timer
{
    protected static $timers = [];

    public static function start($name = null)
    {
        $start = microtime(true);
        if ($name == null) {
            self::$timers[] = $start;
        } else {
            self::$timers[$name] = $start;
        }
    }

    public static function finish($name = null)
    {
        $finish = microtime(true);
        $start = ($name == null) ? $start = end(self::$timers) : $start = self::$timers[$name];
        return round($finish - $start, 2);
    }
}
