<?php
abstract class King23_TemplateView
{
    private $sith;
    protected $_context = array();

    public function __construct()
    {
        $this->sith = King23_Registry::getInstance()->sith;
    }

    protected function render($template, $context)
    {
        $context = array_merge($this->_context, $context);
        echo $this->sith->cachedGet($template)->render($context, $this->sith);
    }

    public function dispatch($action, $request)
    {
        if(!method_exists($this, $action))
            throw new King23_ViewActionDoesNotExistException();
        $this->$action($request);
    }

}