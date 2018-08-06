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
namespace King23\Http\Middleware;

use King23\Core\SettingsInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class BasePathStripper
 *
 * This Middleware will check the settings for a key named "app.basePath", and if found strip this from all requests.
 *
 * @package King23\Http\Middleware
 */
class BasePathStripper implements MiddlewareInterface
{
    /**
     * @var string
     */
    protected $basePath;

    /**
     * BasePathStripper constructor.
     *
     * @param SettingsInterface $settings
     */
    public function __construct(SettingsInterface $settings)
    {
        $this->basePath = $settings->get('app.basePath', '');
    }

    /**
     * strip away the basePath
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $next
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $next) : ResponseInterface
    {
        $path = $request->getUri()->getPath();
        if (!empty($this->basePath) && 0 === strpos($path, $this->basePath)) {
            $cleanPath = substr($path, strlen($this->basePath));

            if ($cleanPath === false) {
                $cleanPath = '';
            }

            $request = $request->withUri($request->getUri()->withPath($cleanPath));
        }
        return $next->handle($request);
    }
}
