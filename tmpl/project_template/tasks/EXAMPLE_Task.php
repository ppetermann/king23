<?php
class EXAMPLE_Task extends King23_CLI_Task
{
    /**
     * documentation for the single tasks
     * @var array
     */
    protected $tasks = array(
        "info" => "General Informative Task",
        "exmaple" => "example",
    );

    /**
     * Name of the module
     */
    protected $name = "EXAMPLE";


    public function example(array $options)
    {
        $this->cli->message("example task call");
        var_dump($options);
    }
}