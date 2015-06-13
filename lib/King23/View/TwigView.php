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
use King23\TwigIntegration\TwigInterface;

/**
 * Basic view for all views who need to use the Twig Templates
 * all templated views should be derived from this
 */
abstract class TwigView extends View
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
     * @var \Psr\Log\LoggerInterface
     */
    protected $log;

    /**
     * public contructor, call this from all derived classes
     *
     * @param TwigInterface $twig
     */
    public function __construct(TwigInterface $twig, \Psr\Log\LoggerInterface $log)
    {
        $this->twig = $twig;
        $this->log = $log;
    }

    /**
     * @return \Psr\Log\LoggerInterface
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
     * @param bool $silent if set to true the method will not echo out the results
     * @return string
     */
    protected function render($template, $context = array(), $silent = false)
    {
        $context = array_merge($this->_context, $context);
        $body = $this->twig->render($template, $context);
        if (!$silent) {
            echo $body;
        }
        return $body;
    }
}
