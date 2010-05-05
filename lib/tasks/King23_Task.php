<?php
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
        "create_project" => "create a new project, requires project name as parameter"
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

}