<?php
namespace King23\Core;

/**
 * Interface SettingsInterface
 *
 * @package Core
 */
interface SettingsInterface {

    /**
     * retrieve a settings value, will return $default if none is found
     * @param string $key
     * @param null|mixed $default
     * @return mixed
     */
    public function get($key, $default=null);

    /**
     * set a settings value
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function set($key, $value);
}
