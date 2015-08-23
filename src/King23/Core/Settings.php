<?php
namespace King23\Core;

class Settings implements SettingsInterface
{
    /**
     * @var array
     */
    protected $data;

    /**
     * retrieve a settings value, will return $default if none is found
     *
     * @param string $key
     * @param null|mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (!isset($this->data[$key])) {
            return $default;
        }
        return $this->data[$key];
    }

    /**
     * set a settings value
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }
}
