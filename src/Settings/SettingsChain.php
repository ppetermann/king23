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

use King23\Settings\SettingsInterface;

class SettingsChain implements SettingsInterface
{
    /** @var SettingsInterface[] */
    protected $settingsProviders = [];

    /**
     * registers a SettingsInterface as a possible provider for settings
     * last one registered is first one to be checked (!)
     *
     * @param SettingsInterface $settings
     * @return SettingsChain
     */
    public function registerSettingsProvider(SettingsInterface $settings): SettingsChain
    {
        $this->settingsProviders[] = $settings;
        return $this;
    }

    /**
     * retrieve a settings value, will return $default if none is found
     * @param string $key
     * @param null|mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        /** @var SettingsInterface[] $providers */
        $providers = array_reverse($this->settingsProviders);
        foreach ($providers as $provider) {
            if (!is_null($setting = $provider->get($key, null))) {
                return $setting;
            }
        }
        return $default;
    }
}
