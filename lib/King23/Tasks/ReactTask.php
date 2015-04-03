<?php
/*
 MIT License
 Copyright (c) 2010 - 2015 Peter Petermann

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

use King23\Core\Registry;
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
    protected $originalServer;

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

        $this->originalServer = $_SERVER;

        $loop = Factory::create();
        $socket = new SocketServer($loop);
        $http = new HttpServer($socket, $loop);
        $http->on('request', [$this, 'handleRequest']);

        $socket->listen($port,$ip);

        Registry::getInstance()->getLogger()->info("starting react based server on $ip:$port");

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
        $_SERVER = $this->originalServer;

        Registry::getInstance()->getLogger()->debug('receiving request for "'.$request->getPath().'"');

        ob_start();
        $return = Router::getInstance()->dispatch($request->getPath());

        $sobody = ob_get_contents();
        ob_end_clean();

        $statusCode = 200;

        $headers = [
            'Content-Type' => 'text/html'
        ];

        if (is_array($return)) {
            Registry::getInstance()->getLogger()->debug('array returned, MistralStaticView style return');
            $body = $return['body'];
            $statusCode = (int) $return['status_code'];
            $headers['Content-Type'] = $return['content-type'];
        } else if (is_string($return) && !empty($return)) {
            Registry::getInstance()->getLogger()->debug('string returned, assuming request body');
            $body = $return;
        } else {
            Registry::getInstance()->getLogger()->debug('nothing returned, assuming stdout has our contents');
            $body = $sobody;
        }
        $response->writeHead($statusCode, $headers);
        $response->end($body);
    }
}
