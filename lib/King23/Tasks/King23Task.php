<?php
/*
 MIT License
 Copyright (c) 2010 - 2013 Peter Petermann

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

namespace King23\Tasks;
/**
 * Provider for main king23 tasks
 */
class King23Task extends \King23\CommandLine\Task
{
    /**
     * documentation for the single tasks
     * @var array
     */
    protected $tasks = array(
        "info" => "General Informative Task",
        "list_modules" => "show a list of all currently available modules",
        "create_project" => "create a new project, requires project name as parameter",
        "doctrine" => "call doctrines cli (requires doctrine to be installed / configured)",
        "shell" => "run boris based king23 shell environment" 
    );

    /**
     * Name of the module
     */
    protected $name = "King23";


    /**
     * List all available modules (from point of script run)
     */
    public function list_modules()
    {
        /* core tasks */
        $tasks = glob(KING23_PATH . '/lib/King23/Tasks/*Task.php');

        /* current project tasks */
        $tasks = array_merge($tasks, glob("./tasks/*/*Task.php"));

        $modules = array();
        foreach($tasks as $taskfile) {
            if(substr($taskfile, 0, strlen(KING23_PATH . '/lib/')) == KING23_PATH . '/lib/')  {
                $modules[substr(basename($taskfile),0,-8)] = str_replace("/", '\\',substr($taskfile,strlen(KING23_PATH . '/lib/'),-4));
            } else {
                $modules[substr(basename($taskfile),0,-8)] = str_replace("/", '\\', substr($taskfile,8,-4));
            }
        }

        // in case of being run in a phar file, we have to cheat here!
        if(defined("KING23_PHAR")) 
        { 
            $modules['King23'] = 'King23\Tasks\King23Task';
            $modules['Mistral'] = 'King23\Tasks\Mistral';
        }

        $this->cli->header("Available Modules:");
        foreach($modules as $module => $class)
        {
            $this->cli->message($module);
        }
    }

    /**
     * create a new project
     * @param array $options should contain name as first option
     * @return int
     */
    public function create_project($options)
    {
        $this->cli->header("King23 (Version: " . \King23\Core\King23::Version .") project creation");
        if(count($options) != 1)
        {
            $this->cli->error("Syntax: king23 King23:create_project <projectname>");
            return 1;
        }
        $name = $options[0];

        if(file_exists($name))
        {
            $this->cli->error("folder with name '$name' allready exists");
            return 1;
        }
        $this->cli->message("copying project template to $name");
        if(!is_writeable("."))
        {
            $this->cli->error("cannot write in current folder");
            $this->cli->warning("exiting");
            return 1;
        }
        exec("cp -r " . KING23_PATH ."/vendor/king23/project_template $name", $retstring, $ret);
        if($ret != 0)
        {
            $this->cli->error("error occured during copying");
            $this->cli->warning("exiting");
            return 1;
        }

        $this->cli->message("Setting rights in $name/cache/");
        exec("chmod -R 777 $name/cache/", $retstring, $ret);
        if($ret != 0)
        {
            $this->cli->error("error occured while setting rights");
            $this->cli->warning("exiting");
            return 1;
        }
        $this->cli->positive("Project: " . \King23\CommandLine\OutputWriter::FONT_Bold . $name . \King23\CommandLine\OutputWriter::COLOR_FG_Green ." created");
        $this->cli->message("Please run composer.phar install in the newly created project folder");
        return 0;
    }

    /**
     * print out information
     */
    public function info()
    {
        $this->cli->header("King23 Version: ");
        $this->cli->message(\King23\Core\King23::Version);
        $this->cli->header("Description: ");
        $this->cli->message(\King23\Core\King23::Description);
        $this->cli->header("Authors: ");
        $this->cli->message(\King23\Core\King23::Authors);
        $this->cli->message();
        parent::info();
    }
    
    /**
     * doctrine task, allows to call doctrines cli (if doctrine is installed) 
     * @param array $options parameters to  be passed to doctrines cli
     */
    public function doctrine(array $options)
    {   
        $this->cli->header("King23 Doctrine CLI wrapper");
        if(is_null(\King23\Core\Registry::getInstance()->doctrine))
        {
            $this->cli->error("Doctrine is not configured (could not find doctrine configuration in King23_Registry)");
            return;
        } 
        if(!class_exists("Doctrine"))
        {
            $this->cli->error("Doctrine class not found, please ensure Doctrine is installed and configured");
            return;
        }
        array_unshift($options, "king23 King23:doctrine");
        $cli = new Doctrine_Cli(\King23\Core\Registry::getInstance()->doctrine["config"]);
        $cli->run($options);
    }

    /**
     * start a boris based shell with loaded king23 environment
     * @ param array $options parameters array for compatibility reasons, ot used 
     */
    public function shell(array $options) {
        if(!class_exists('\Boris\Boris', true))
            die("Boris not installed, make sure your composer environment has d11wtq/boris in its list");
        if(defined("KING23_CLI_PROMPT"))
            $prompt = KING23_CLI_PROMPT;
        else 
            $prompt = "king23> ";
        $boris = new \Boris\Boris($prompt);
        $boris->onStart(function() { 
            echo "King23 Version " . \King23\Core\King23::Version . " Boris based Shell Environment starting\n";
        });
        $boris->start();
    }
}
