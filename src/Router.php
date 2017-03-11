<?php

namespace On2Media\Zeptowaf;

class Router
{
    private $request;
    private $container;

    private $routes;

    public function __construct(Request $request, &$container)
    {
        $this->request = $request;
        $this->container = &$container;
        $this->routes = [
            'GET' => [],
            'POST' => [],
        ];
    }

    public function get($regexp, $controller, $method)
    {
        $this->action('GET', $regexp, $controller, $method);
    }

    public function post($regexp, $controller, $method)
    {
        $this->action('POST', $regexp, $controller, $method);
    }

    private function action($requestMethod, $regexp, $controller, $method)
    {
        $this->routes[$requestMethod][$regexp] = [
            'controller' => $controller,
            'method' => $method,
        ];
    }

    public function route()
    {
        $availableRoutes = $this->routes[$this->request->getMethod()];
        foreach ($availableRoutes as $regexp => $route) {
            if (preg_match($regexp, $this->request->getUri(), $params) === 1) {
                $ctrlName = $route['controller'];
                $ctrl = new $ctrlName($this->request, $this->container);
                if (!is_a($ctrl, '\On2Media\Zeptowaf\Routable')) {
                    throw new \Exception('Controller isn\'t routable');
                }
                return $ctrl->{$route['method']}($params);
            }
        }

        throw new NotFoundException('Page not found');
    }
}
