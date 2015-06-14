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

use King23\View\Exceptions\ViewActionDoesNotExistException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * base for all views
 */
abstract class View
{
    /**
     * @return \Psr\Log\LoggerInterface
     */
    abstract protected function getLogger();

    /**
     * function to dispatch requests comming throuh the router
     *
     * @param $action
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $routeParams
     * @return ResponseInterface
     * @throws ViewActionDoesNotExistException
     */
    public function dispatch($action, ServerRequestInterface $request, ResponseInterface $response, array $routeParams)
    {
        $this->getLogger()->debug('dispatching to action: '.$action);
        if (!method_exists($this, $action) && !method_exists($this, '__call')) {
            throw new ViewActionDoesNotExistException();
        }
        $response = $this->$action($routeParams, $request, $response);
        return $response;
    }

    /**
     * redirect by sending a http location header
     *
     * @param  $location
     */
    protected function redirect($location)
    {
        header("Location: $location");
    }
}
