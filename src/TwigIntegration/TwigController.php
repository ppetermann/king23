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
namespace King23\TwigIntegration;

use King23\Controller\Controller;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Base controller for all controllers which need to use the Twig Templates
 */
abstract class TwigController extends Controller
{
    /**
     * twig environment object, pulled from registry->twig
     */
    private $twig;

    /**
     * assoc array containing all vars to use in template
     *
     * @var array
     */
    protected $_context = array();

    /**
     * @var LoggerInterface
     */
    protected $log;

    /**
     * @var ResponseFactoryInterface
     */
    protected $responseFactory;

    /**
     * public contructor, call this from all derived classes
     *
     * @param TwigInterface $twig
     * @param LoggerInterface $log
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(
        TwigInterface $twig,
        LoggerInterface $log,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->twig = $twig;
        $this->log = $log;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @return LoggerInterface
     */
    protected function getLogger()
    {
        return $this->log;
    }

    /**
     * render template with context, will merge context with allready known
     * context
     *
     * @param string $template
     * @param array $context
     * @return string
     */
    protected function render($template, $context = [])
    {
        $context = array_merge($this->_context, $context);
        $body = $this->twig->render($template, $context);
        return $body;
    }

    /**
     * @param string $template
     * @param array $context
     * @return ResponseInterface
     */
    protected function renderResponse(string $template, array $context = []) : ResponseInterface
    {
        $body = $this->render($template, $context);
        $response = $this->responseFactory->createResponse();
        $response->getBody()->write($body);
        return $response;
    }
}
