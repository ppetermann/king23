<?php
/**
 * Basic view for all views who need to use the SithTemplates
 * all templated views should be derived from this
 */
abstract class King23_TemplateView
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