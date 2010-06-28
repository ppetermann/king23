<?php
/*
 MIT License
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
 * Basic view for all views who need to use the SithTemplates
 * all templated views should be derived from this
 */
abstract class King23_TemplateView extends King23_View
{
    /**
     * Sith Object, pulled from registry->sith
     */
    private $sith;

    /**
     * assoc array containing all vars to use in template
     * @var array
     */
    protected $_context = array();

    /**
     * public contructor, call this from all derived classes
     */
    public function __construct()
    {
        $this->sith = King23_Registry::getInstance()->sith;
    }

    /**
     * render template with context, will merge context with allready known
     * context
     * @param string $template
     * @param array $context
     */
    protected function render($template, $context = array())
    {
        $context = array_merge($this->_context, $context);
        echo $this->sith->cachedGet($template)->render($context, $this->sith);
    }

    /**
     * function to dispatch requests comming throuh the router
     * @param <type> $action
     * @param <type> $request 
     */
    public function dispatch($action, $request)
    {
        if(!method_exists($this, $action))
            throw new King23_ViewActionDoesNotExistException();
        $this->$action($request);
    }

}
