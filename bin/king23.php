#!/usr/bin/php
<?php
/**
 * King23 command line script
 */

// Lets have all errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

// lets find out where we are, and load a fitting classloader
define('KING23_PATH', realpath(dirname(__FILE__) . "/.."));
require_once(KING23_PATH . "/core/King23_Classloader.php");
spl_autoload_register("King23_Classloader::load");

King23_Classloader::init(KING23_PATH);
if(file_exists("./tasks/"))
    King23_Classloader::init("./tasks/");

$cli = King23_CLI::getInstance();

/* core tasks */
$tasks = glob(KING23_PATH . '/tasks/*_Task.php');

/* current project tasks */
$tasks = array_merge($tasks, glob("./tasks/*_Task.php"));

$modules = array();
foreach($tasks as $taskfile)
    $modules[substr(basename($taskfile),0,-9)] = substr(basename($taskfile),0,-4);

if($argc > 1)
{
    $module_task = explode(":", $argv[1]);
    if(count($module_task) > 2)
    {
        $cli->error("Syntax Error: " .$argv[1]. " is not <module>[:<task>]");
        return;
    } elseif(count($module_task) == 1) {
        $cli->warning($module_task[0] ." called without task, assuming 'info' ");
        $module = $module_task[0];
        $task = "info";
    } else {
        $module = $module_task[0];
        $task = $module_task[1];
    }

    if(!isset($modules[$module]))
    {
        $cli->error("Module: $module does not exist.");
        return;
    } else {
        $object= new $modules[$module]();
        if(!method_exists($object, $task))
        {
            $cli->error("Module: $module does not know a taks $task");
            return;
        }
        array_shift($argv); // remove script
        array_shift($argv); // remove module:task
        $object->$task($argv); // call!
    }
} else {
        $cli->header("King23 CLI (Version: " . King23::Version . ")");
        $cli->message("Syntax: king23 <taskmodule>:<task>");
        $cli->message();

        $object = new King23_Task();
        $object->list_modules();


}

/*foreach($modules as $name => $class)
{
    $cli->message($name);
}*/

