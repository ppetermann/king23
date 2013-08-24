<?php
namespace King23\Tasks;

class MistralTask extends \King23\CommandLine\Task
{
    /**
     * documentation for the single tasks
     *
     * @var array
     */
    protected $tasks = array(
        "info" => "General Informative Task",
        "run" => "Run the Mistral Server [ip[:port]]",
    );
    /**
     * Name of the module
     */
    protected $name = "Mistral";


    // this is a bit of a hack, the original $_SERVER content will be saved here
    // so it can be used as a basis for a new/clean $_SERVER on each request
    // since the php run is not terminated this is the only way to ensure that
    // seperate requests dont interfere with eachother
    protected $original_Server;

    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
        $router = \King23\Core\Router::getInstance();
        $router->addRoute("/images/", '\King23\View\MistralStaticView', "images", array("filename"));
        $router->addRoute("/css/", '\King23\View\MistralStaticView', "css", array('filename'));
        $router->addRoute("/js/", '\King23\View\MistralStaticView', "js", array('filename'));
    }

    /**
     * Task to launch the mistral server
     *
     * @param array $options
     */
    public function run($options)
    {
        // default values
        $ip = '0.0.0.0';
        $port = 3000;

        // overwrite options if given
        if (isset($options[0]) && !empty($options[0])) {
            $details = explode(":", $options[0]);
            $ip = $details[0];
            if (isset($details[1])) {
                $port = $details[1];
            }
        }
        $keepalive = 3;
        $this->cli->header("King23 // Mistral Webserver ");
        $this->cli->message("launching server at $ip:$port");
        mistral_init($ip, $port, $keepalive);
        mistral_register_callback(array($this, 'handleRequest'));
        $this->original_Server = $_SERVER;
        mistral_start();
    }

    /**
     * method to be called on each request, should dispatch call to router
     *
     * @param array $request
     * @return array
     */
    public function handleRequest($request)
    {
        $_SERVER = $this->original_Server;
        $_SERVER = array_merge($_SERVER, $request);
        $this->cli->message('receiving request for "'.$_SERVER['REQUEST_URI'].'"');
        ob_start();
        $return = \King23\Core\Router::getInstance()->dispatch($_SERVER["REQUEST_URI"]);

        $sobody = ob_get_contents();
        ob_end_clean();
        if (is_array($return)) // we got an array back, so we assume it has all data for answering the request
        {
            $this->cli->message('array returned, assuming request can be answered');
            return $return;
        }

        if (is_string($return) && !empty($return)) {
            $this->cli->message('string returned, assuming request body');
            $body = $return;
        } else {
            $this->cli->message('nothing returned, assuming stdout has our contents');
            $body = $sobody;
        }
        return array(
            'status_code' => '200 OK',
            'connection' => 'close',
            'content-type' => 'text/html', // testing purposes
            'body' => $body
        );
    }
}
