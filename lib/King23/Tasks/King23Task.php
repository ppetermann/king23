<?php
/*
 MIT License
 Copyright (c) 2010 - 2014 Peter Petermann

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
use Boris\Boris;
use King23\CommandLine\Task;
use King23\Core\King23;

/**
 * Provider for main king23 tasks
 */
class King23Task extends Task
{
    /**
     * documentation for the single tasks
     *
     * @var array
     */
    protected $tasks = array(
        "info" => "General Informative Task",
        "listModules" => "show a list of all currently available modules",
        "createProject" => "create a new project, requires project name as parameter",
        "shell" => "run boris based king23 shell environment"
    );

    /**
     * Name of the module
     */
    protected $name = "King23";


    /**
     * List all available modules (from point of script run)
     */
    public function listModules()
    {
        /* core tasks */
        $tasks = glob(KING23_PATH.'/lib/King23/Tasks/*Task.php');

        /* current project tasks */
        $tasks = array_merge($tasks, glob("./tasks/*/*Task.php"));

        $modules = array();
        foreach ($tasks as $taskfile) {
            if (substr($taskfile, 0, strlen(KING23_PATH.'/lib/')) == KING23_PATH.'/lib/') {
                $modules[substr(basename($taskfile), 0, -8)] = str_replace(
                    "/",
                    '\\',
                    substr($taskfile, strlen(KING23_PATH.'/lib/'), -4)
                );
            } else {
                $modules[substr(basename($taskfile), 0, -8)] = str_replace("/", '\\', substr($taskfile, 8, -4));
            }
        }

        // in case of being run in a phar file, we have to cheat here!
        if (defined("KING23_PHAR")) {
            $modules['King23'] = 'King23\Tasks\King23Task';
            $modules['Mistral'] = 'King23\Tasks\Mistral';
        }

        $this->cli->header("Available Modules:");
        foreach ($modules as $module => $class) {
            $this->cli->message($module);
        }
    }

    /**
     * create a new project
     *
     * @param array $options should contain name as first option
     * @return int
     * @deprecated
     */
    public function createProject($options)
    {
        $this->cli->error("createProject is deprecated, use composer create-project king23/project_template");
        return 0;
    }

    /**
     * print out information
     */
    public function info()
    {
        $this->cli->header("King23 Version: ");
        $this->cli->message(King23::VERSION);
        $this->cli->header("Description: ");
        $this->cli->message(King23::DESCRIPTION);
        $this->cli->header("Authors: ");
        $this->cli->message(King23::AUTHORS);
        $this->cli->message();
        parent::info();
    }

    /**
     * start a boris based shell with loaded king23 environment
     * @ param array $options parameters array for compatibility reasons, ot used
     */
    public function shell(array $options)
    {
        if (!class_exists('\Boris\Boris', true)) {
            die("Boris not installed, make sure your composer environment has d11wtq/boris in its list");
        }
        if (defined("KING23_CLI_PROMPT")) {
            $prompt = KING23_CLI_PROMPT;
        } else {
            $prompt = "king23> ";
        }
        $boris = new Boris($prompt);
        $boris->onStart(
            function () {
                echo "King23 Version ". King23::VERSION." Boris based Shell Environment starting\n";
            }
        );
        $boris->start();
    }
}
