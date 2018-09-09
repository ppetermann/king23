<?php
/*
 MIT License
 Copyright (c) 2010 - 2018 Peter Petermann

 Permission is hereby granted, free of charge, to any person
 obtaining a copy of this software and associated documentation
 files (the "Software"), to deal in the Software without
 restriction, including without limitation the rights to use,
 copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the
 Software is furnished to do so, subject to the following
 conditions:

 The above copyright notice and this permission notice shall be
 included in all copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 OTHER DEALINGS IN THE SOFTWARE.

*/

namespace King23\Settings;

class JsonSettings implements SettingsInterface
{
    private $data = [];

    /**
     * @param string $filename
     * @return JsonSettings
     */
    public static function fromFilename(string $filename): JsonSettings
    {
        return static::fromJsonString(file_get_contents($filename));
    }

    /**
     * @param string $string
     * @return JsonSettings
     */
    public static function fromJsonString(string $string): JsonSettings
    {

        // @todo remove once minimum is php 7.3
        if (!defined("JSON_THROW_ON_ERROR")) define("JSON_THROW_ON_ERROR", 4194304);
        $settings = new JsonSettings();
        $settings->data = json_decode($string, true, 512, JSON_THROW_ON_ERROR);
        return $settings;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return array|int|mixed|null|string
     */
    public function get($key, $default = null)
    {
        $keys = explode(".", $key);
        $result = $this->getFromSubKeys($keys, $this->data);
        if (is_null($result)) {
            return $default;
        }
        return $result;
    }

    /**
     * @param array $keys
     * @param array $data
     * @return null|string|int|array
     */
    private function getFromSubKeys(array $keys, array $data)
    {
        $key = array_shift($keys);

        // key doesn't exist, so we can return null
        if (!array_key_exists($key, $data)) {
            return null;
        }
        // key is the last key, so we can return the contents
        if (count($keys) == 0) {
            return $data[$key];
        }

        // more subkeys, so lets dive in
        return $this->getFromSubKeys($keys, $data[$key]);
    }

}
