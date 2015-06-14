<?php
namespace King23\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Application
 *
 * @package King23\Http
 */
class Application implements ApplicationInterface
{
    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var ResponseInterface
     */
    private $response;
    /**
     * @var MiddlewareQueueInterface
     */
    private $queue;

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param MiddlewareQueueInterface $queue
     */
    public function __construct(
        ServerRequestInterface $request,
        ResponseInterface $response,
        MiddlewareQueueInterface $queue
    ) {

        $this->request = $request;
        $this->response = $response;
        $this->queue = $queue;
    }

    /**
     * run a http based application
     */
    public function run()
    {
        /** @var ResponseInterface $response */
        $response = $this->queue->run($this->request, $this->response);

        // http status
        $reasonPhrase = $response->getReasonPhrase();
        $reasonPhrase = ($reasonPhrase ? ' '.$reasonPhrase : '');
        header(sprintf('HTTP/%s %d%s', $response->getProtocolVersion(), $response->getStatusCode(), $reasonPhrase));

        // additional headers
        foreach ($response->getHeaders() as $header => $values) {
            $first = true;
            foreach ($values as $value) {
                header(sprintf('%s: %s', $header, $value), $first);
                $first = false;
            }
        }
        echo $response->getBody();
    }
}
