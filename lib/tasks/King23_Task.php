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
 * Provider for main king23 tasks
 */
class King23_Task extends King23_CLI_Task
{
    /**
     * documentation for the single tasks
     * @var array
     */
    protected $tasks = array(
        "info" => "General Informative Task",
        "list_modules" => "show a list of all currently available modules",
        "create_project" => "create a new project, requires project name as parameter",
        "doctrine" => "call doctrines cli (requires doctrine to be installed / configured)"
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
        $tasks = glob(KING23_PATH . '/lib//tasks/*_Task.php');

        /* current project tasks */
        $tasks = array_merge($tasks, glob("./tasks/*_Task.php"));

        $modules = array();
        foreach($tasks as $taskfile)
            $modules[substr(basename($taskfile),0,-9)] = substr(basename($taskfile),0,-4);

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
        $this->cli->header("King23 (Version: " . King23::Version .") project creation");
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
        exec("cp -r " . KING23_PATH ."/tmpl/project_template $name", $retstring, $ret);
        if($ret != 0)
        {
            $this->cli->error("error occured during copying");
            $this->cli->warning("exiting");
            return 1;
        }
        $this->cli->message("creating symlink $name/lib/King23 to point to " . KING23_PATH);

        if(!@symlink(KING23_PATH, "$name/lib/King23"))
            $this->cli->warning("symlink creation has failed, you have to do it manualy");

        $this->cli->message("Setting rights in $name/template_c/");
        exec("chmod -R 777 $name/templates_c", $retstrign, $ret);
        if($ret != 0)
        {
            $this->cli->error("error occured while setting rights");
            $this->cli->warning("exiting");
            return 1;
        }
        $this->cli->positive("Project: " . King23_CLI_OutputWriter::FONT_Bold . $name . King23_CLI_OutputWriter::COLOR_FG_Green ." created");
        return 0;
    }

    /**
     * print out information
     * @param array $options not used, only for compatibility
     */
    public function info($options)
    {
        $this->cli->header("King23 Version: ");
        $this->cli->message(King23::Version);
        $this->cli->header("Description: ");
        $this->cli->message(King23::Description);
        $this->cli->header("Authors: ");
        $this->cli->message(King23::Authors);
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
        if(is_null(King23_Registry::getInstance()->doctrine))
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
        $cli = new Doctrine_Cli(King23_Registry::getInstance()->doctrine["config"]);
        $cli->run($options);
    }
}
