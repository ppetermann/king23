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
namespace King23\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Application
 *
 * @package King23\Http
 */
class Application implements ApplicationInterface
{
    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var ResponseInterface
     */
    private $response;
    /**
     * @var MiddlewareQueueInterface
     */
    private $queue;

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param MiddlewareQueueInterface $queue
     */
    public function __construct(
        ServerRequestInterface $request,
        ResponseInterface $response,
        MiddlewareQueueInterface $queue
    ) {

        $this->request = $request;
        $this->response = $response;
        $this->queue = $queue;
    }

    /**
     * run a http based application
     */
    public function run()
    {
        /** @var ResponseInterface $response */
        $response = $this->queue->run($this->request, $this->response);

        // http status
        $reasonPhrase = $response->getReasonPhrase();
        $reasonPhrase = ($reasonPhrase ? ' '.$reasonPhrase : '');
        header(sprintf('HTTP/%s %d%s', $response->getProtocolVersion(), $response->getStatusCode(), $reasonPhrase));

        // additional headers
        foreach ($response->getHeaders() as $header => $values) {
            $first = true;
            foreach ($values as $value) {
                header(sprintf('%s: %s', $header, $value), $first);
                $first = false;
            }
        }
        echo $response->getBody();
    }
}
