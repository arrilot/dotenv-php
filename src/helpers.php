<?php

use Arrilot\DotEnv\DotEnv;

if (! function_exists('env')) {
    /**
     * Get env variable.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        return DotEnv::get($key, $default);
    }
}
