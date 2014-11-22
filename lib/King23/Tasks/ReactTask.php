<?php
namespace King23\Tasks;

use King23\Core\Router;
use React\EventLoop\Factory;
use React\Http\Request;
use React\Http\Response;
use React\Http\Server as HttpServer;
use React\Socket\Server as SocketServer;

class ReactTask extends \King23\CommandLine\Task
{
    /**
     * documentation for the single tasks
     *
     * @var array
     */
    protected $tasks = array(
        "info" => "General Informative Task",
        "run" => "Run the React Server [ip[:port]]",
    );
    /**
     * Name of the module
     */
    protected $name = "React";


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
        $router = Router::getInstance();
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

        $this->original_Server = $_SERVER;

        $loop = Factory::create();
        $socket = new SocketServer($loop);
        $http = new HttpServer($socket, $loop);
        $http->on('request', [$this, 'handleRequest']);

        $socket->listen($port,$ip);
        $this->cli->message("starting server on $ip, $port");

        $loop->run();

    }

    /**
     * method to be called on each request, should dispatch call to router
     *
     * @param Request $request
     * @param Response $response
     */
    public function handleRequest(Request $request, Response $response)
    {
        $_SERVER = $this->original_Server;

        $this->cli->message('receiving request for "'.$request->getPath().'"');
        ob_start();
        $return = Router::getInstance()->dispatch($request->getPath());

        $sobody = ob_get_contents();
        ob_end_clean();

        if (is_string($return) && !empty($return)) {
            $this->cli->message('string returned, assuming request body');
            $body = $return;
        } else {
            $this->cli->message('nothing returned, assuming stdout has our contents');
            $body = $sobody;
        }
        $response->writeHead(200, [
            'Content-Type' => 'text/html'
        ]);

        $response->end($body);
    }
}
