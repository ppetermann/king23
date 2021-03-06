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
namespace King23\Http\Middleware\Whoops;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Class Whoops
 * simple middleware to use whoops within king23 based apps
 *
 * this should also work with other psr-7 middlewars as long as they use
 * the request,response,callable-next signature
 *
 * @package King23\Whoops
 */
class Whoops implements MiddlewareInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * Whoops constructor.
     * @param ResponseFactoryInterface $response
     */
    public function __construct(ResponseFactoryInterface $response)
    {
        $this->responseFactory = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $next) : ResponseInterface
    {
        try {
            $response = $next->handle($request);
        } catch (\Exception $e){
            $whoops = $this->getWhoops();
            $body = $whoops->handleException($e);

            $response = $this->responseFactory->createResponse(500);
            $response->getBody()->write($body);
        }
        return $response;
    }

    /**
     * @return Run
     */
    protected function getWhoops()
    {
        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->writeToOutput(false);
        $whoops->allowQuit(false);
        return $whoops;
    }
}
