<?php
/*
 MIT License
 Copyright (c) 2010 - 2015 Peter Petermann

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
namespace King23\View;

use King23\Core\Exceptions\Exception;

class MistralStaticView extends View
{

    /**
     * __call method, should return file with finfo guessed mimetype
     *
     * @param string $method
     * @param array $params
     * @return array|bool
     */
    public function __call($method, $params)
    {
        return $this->getFile($method, $params[0]['filename']);
    }

    /**
     * special call for js, to add mimetypea
     *
     * @param array $request
     * @return array|bool
     */
    public function js($request)
    {
        return $this->getFile('js', $request['filename'], 'text/javascript');
    }

    /**
     * special call for css to add mimetype
     *
     * @param array $request
     * @return array|bool
     */
    public function css($request)
    {
        return $this->getFile('css', $request['filename'], 'text/css');
    }


    /**
     * returns array with contents, mimetype and http status for the file
     *
     * @param string $path
     * @param string $filename
     * @param bool|string $mime , string if given, false if guessing
     * @throws \King23\Core\Exceptions\Exception
     * @return array|bool
     */
    private function getFile($path, $filename, $mime = false)
    {
        if (!defined("APP_PATH")) {
            throw new Exception("APP_PATH was not defined");
        }

        $file = APP_PATH.'/public/'.$path.'/'.$filename;
        if (!file_exists($file)) {
            return false; // better handling necessary!
        }
        if ($data = file_get_contents($file)) {
            if (!$mime) {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimetype = $finfo->file($file);
            } else {
                $mimetype = $mime;
            }
            return array(
                'status_code' => '200 OK',
                'connection' => 'close',
                'content-type' => $mimetype,
                'body' => $data
            );
        }
        return array('status_code' => '404 NOT FOUND', 'connection' => 'close');
    }
}
