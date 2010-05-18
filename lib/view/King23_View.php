<?php
/**
 * base for all views
 */
abstract class King23_View
{
    abstract function dispatch($action, $request);
}