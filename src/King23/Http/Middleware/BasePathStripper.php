<?php
namespace King23\Http\Middleware;

use King23\Core\SettingsInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class BasePathStripper
 *
 * This Middleware will check the settings for a key named "app.basePath", and if found strip this from all requests.
 *
 * @package King23\Http\Middleware
 */
class BasePathStripper
{
    /**
     * @var string
     */
    protected $basePath;

    /**
     * BasePathStripper constructor.
     *
     * @param SettingsInterface $settings
     */
    public function __construct(SettingsInterface $settings)
    {
        $this->basePath = $settings->get('app.basePath', '');
    }

    /**
     * strip away the basePath
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $path = $request->getUri()->getPath();
        if (!empty($this->basePath) && 0 === strpos($path, $this->basePath)) {
            $cleanPath = substr($path, strlen($this->basePath));

            if ($cleanPath === false) {
                $cleanPath = '';
            }

            $request = $request->withUri($request->getUri()->withPath($cleanPath));
        }
        return $next($request, $response);
    }
}
