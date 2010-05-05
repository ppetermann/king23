<?php
class King23_Task extends King23_CLI_Task
{
    protected $tasks = array(
        "info" => "General Informative Task",
        "list_modules" => "show a list of all currently available modules"
    );

    protected $name = "King23";

    public function list_modules()
    {
        /* core tasks */
        $tasks = glob(KING23_PATH . '/tasks/*_Task.php');

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

}