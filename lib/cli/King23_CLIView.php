<?php
/*
 LICENSE
 Copyright (c) 2010 Peter Petermann

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

/**
 * Basis class for CLI Views
 * @deprecated
 */
abstract class King23_CLIView
{
    protected $log;

    public function __construct() // php! rofl!
    {
        if(isset(King23_Registry::getInstance()->cli_log))
            $this->log = King23_Registry::getInstance()->cli_log;
        else 
            $this->log = new King23_Log();
    }

    public function dispatch($action, $request)
    {
        if(!method_exists($this, $action))
            throw new King23_ViewActionDoesNotExistException();
        $this->$action($request);
    }

    protected function log($message) // conveniance
    {
        $this->log->log($message);
    }
}