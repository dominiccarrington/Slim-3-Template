<?php
/**
 * Register middleware for the slim framework
 *
 * NOTE:
 * CSRF protection does not need to be called within here.
 */

$app->add(new \App\Middleware\TwigValuesMiddleware($app));
