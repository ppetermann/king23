<?php
/*
 MIT License
 Copyright (c) 2010 - 2014 Peter Petermann

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
namespace King23\CommandLine\Configurator;

use King23\CommandLine\CLI;
use King23\CommandLine\Configurator\Options\ConfigurationOption;
use King23\Core\Exceptions\Exception;

/**
 * Class Configurator allowes for simple CLI based configuration questions
 * @package King23\CommandLine\Configurator
 */
class Configurator
{
    protected $config;
    protected $configFile;

    /**
     * @param string $configFile
     * @param array $defaults
     */
    public function __construct($configFile, $defaults)
    {
        $this->configFile = $configFile;

        $this->initialize($configFile, $defaults);
    }

    /**
     * @param string $configFile
     * @param array $defaults
     * @return array|boolean
     * @throws \King23\Core\Exceptions\Exception
     */
    protected function initialize($configFile, $defaults)
    {

        $config = $this->readConfig($configFile);

        if (!$config) {
            $config = $defaults;
        }

        if (!$config) {
            throw new Exception("config ($configFile) file not found, and missing defaults");
        }

        $this->config = $config;
    }

    /**
     * read array from file
     * @param string $filename
     * @return bool|array
     */
    protected function readConfig($filename)
    {
        if (!file_exists($filename)) {
            return false;
        }
        return unserialize(file_get_contents($filename));
    }

    /**
     * writes current configuration to file
     * @throws \King23\Core\Exceptions\Exception
     */
    public function save()
    {
        if (CLI::getInstance()->confirm("do you want to save the configuration?")) {
            if (file_exists($this->configFile) && !is_writable($this->configFile)) {
                throw new Exception("config file (". $this->configFile . ") not writable");
            }
            file_put_contents($this->configFile, serialize($this->config));

        }
    }

    /**
     * prints current configuration to stdout
     */
    public function showConfig()
    {
        $cli = CLI::getInstance();
        $cli->header("configuration: ");

        foreach ($this->config as $key => $value) {
            $cli->message("Option: $key => $value");
        }
        $cli->header("");
    }

    /**
     * executes the questioning of the user
     */
    public function askConfiguration()
    {
        /** @var ConfigurationOption $value */
        foreach ($this->config as $key => $value) {
            $this->config[$key] = $value->askForValue();
        }
    }

    /**
     * returns an array of config options
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }
}
