<?php
require('Route.php');

class Router
{
    private array $routes = [];
    private string $baseURL = '';

    /**
     * Method __construct
     *
     * @param string $baseURL [explicite description]
     *
     * @return void
     */
    public function __construct(string $baseURL = '')
    {
        $this->baseURL = $baseURL;
    }

    /**
     * Method getRoutes
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Method getBaseURL
     *
     * @return string
     */
    public function getBaseURL(): string
    {
        return $this->baseURL;
    }

    /**
     * Method addRoute
     *
     * @param string $pathURL [explicite description]
     * @param string $requestMethod [explicite description]
     * @param mixed $callback [explicite description]
     *
     * @return void
     */
    private function addRoute(string $pathURL, string $requestMethod, mixed $callback): void
    {
        if (!empty($this->baseURL)) {
            $pathURL = $this->baseURL . $pathURL;
        }
        $route = new Route($pathURL, $requestMethod, $callback);
        $this->routes[] = $route;
    }

    /**
     * Method findRoute
     *
     * @param object $request [explicite description]
     *
     * @return object|bool
     */
    public function findRoute(object $request): object|bool
    {
        foreach ($this->routes as $route) {
            $matchParams = [];
            preg_match_all($route->pathURIRegex, $request->requestURI, $matchParams);

            if (!empty($matchParams[0])) {
                if (($matchParams[0][0] === $request->requestURI || $matchParams[0][0]  . '/' === $request->requestURI) && $route->requestMethod === $request->requestMethod) {
                    switch ($route->requestMethod) {
                        case 'GET': 
                        case 'DELETE': {
                                foreach ($route->pathArgUnits as $arg) {
                                    $request->params[$arg] = $matchParams[$arg][0];
                                }
                                break;
                        }
                        case 'POST':
                        case 'PUT': {
                                $request->body = json_decode(file_get_contents('php://input'), true);
                                break;
                        }
                        case 'PATCH': {
                                foreach ($route->pathArgUnits as $arg) {
                                    $request->params[$arg] = $matchParams[$arg][0];
                                }
                                $request->body = json_decode(file_get_contents('php://input'), true);
                                break;
                        }
                    }

                    return $route;
                }
            }
        }

        return false;
    }

    /**
     * Method get
     *
     * @param string $path [explicite description]
     * @param mixed $callback [explicite description]
     *
     * @return void
     */
    public function get(string $path, mixed $callback): void
    {
        $this->addRoute($path, 'GET', $callback);
    }

    /**
     * Method post
     *
     * @param string $path [explicite description]
     * @param mixed $callback [explicite description]
     *
     * @return void
     */
    public function post(string $path, mixed $callback): void
    {
        $this->addRoute($path, 'POST', $callback);
    }

    /**
     * Method delete
     *
     * @param string $path [explicite description]
     * @param mixed $callback [explicite description]
     *
     * @return void
     */
    public function delete(string $path, mixed $callback): void
    {
        $this->addRoute($path, 'DELETE', $callback);
    }

    /**
     * Method put
     *
     * @param string $path [explicite description]
     * @param mixed $callback [explicite description]
     *
     * @return void
     */
    public function put(string $path, mixed $callback): void
    {
        $this->addRoute($path, 'PUT', $callback);
    }

    /**
     * Method patch
     *
     * @param string $path [explicite description]
     * @param mixed $callback [explicite description]
     *
     * @return void
     */
    public function patch(string $path, mixed $callback): void
    {
        $this->addRoute($path, 'PATCH', $callback);
    }
}
