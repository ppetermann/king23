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
 * King23_CLI basis task, should be parent of all tasks
 */
abstract class King23_CLI_Task
{
    /**
     * list of available tasks (key) and their description (value)
     * @var Array
     */
    protected $tasks = array();

    /**
     * Name of the Task Module
     * @var String
     */
    protected $name = "MISSING NAME";

    
    /**
     * King23_CLI instance for conveniance
     * @var King23_CLI
     */
    protected $cli;


    /**
     * constructor, call parent::__construct from derived classes
     */
    public function __construct()
    {
        $this->cli = King23_CLI::getInstance();
    }


    /**
     * returns tasks array
     * @return array
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * print out info about the task module
     */
    public function info()
    {
        $this->cli->header("Module " . $this->name);
        $this->cli->header("-----------------------");
        $this->_info();
    }
    

    /**
     * might be called from info tasks to write basic information about each task
     */
    public function _info()
    {
        foreach($this->tasks as $name => $description)
        {
            $this->cli->header("Task: " . King23_CLI_OutputWriter::COLOR_FGL_Blue . $name);
            $this->cli->header("Description:");
            $this->cli->message($description);
            $this->cli->message();
        }
    }
}
