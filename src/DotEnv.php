<?php

namespace Arrilot\DotEnv;

use Arrilot\DotEnv\Exceptions\MissingVariableException;

class DotEnv
{
    /**
     * Key-value storage.
     *
     * @var array
     */
    protected static $variables = [];

    /**
     * Required variables.
     *
     * @var array
     */
    protected static $required = [];

    /**
     * Load .env.php file or array.
     *
     * @param string|array $source
     * @return void
     */
    public static function load($source)
    {
        self::$variables = is_array($source) ? $source : require $source;

        foreach (self::$required as $key) {
            if (!isset(self::$variables[$key])) {
                throw new MissingVariableException(".env variable '{$key}' is missing");
            }
        }
    }

    /**
     * Get env variables.
     *
     * @return array
     */
    public static function all()
    {
        return self::$variables;
    }

    /**
     * Get env variable.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return isset(self::$variables[$key]) ? self::$variables[$key] : $default;
    }

    /**
     * Set env variable.
     *
     * @param string|array $keys
     * @param mixed $value
     * @return void
     */
    public static function set($keys, $value = null)
    {
        if (is_array($keys)) {
            foreach ($keys as $key => $val) {
                self::$variables[$key] = $val;
            }
        } else {
            self::$variables[$keys] = $value;
        }
    }

    /**
     * Delete all variables.
     *
     * @return void
     */
    public static function flush()
    {
        self::$variables = [];
    }

    /**
     * Set required variables.
     *
     * @param array $variables
     */
    public static function setRequired(array $variables)
    {
        self::$required = $variables;
    }
}
