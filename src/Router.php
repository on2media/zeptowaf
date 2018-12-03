<?php

namespace On2Media\Zeptowaf;

class Router
{
    private $request;
    private $container;

    private $routes;
    private $before = [];

    public function __construct(Request $request, &$container)
    {
        $this->request = $request;
        $this->container = &$container;
        $this->routes = [];
    }

    public function get($regexp, $controller, $method)
    {
        $this->action('GET', $regexp, $controller, $method);
    }

    public function post($regexp, $controller, $method)
    {
        $this->action('POST', $regexp, $controller, $method);
    }

    public function put($regexp, $controller, $method)
    {
        $this->action('PUT', $regexp, $controller, $method);
    }

    public function delete($regexp, $controller, $method)
    {
        $this->action('DELETE', $regexp, $controller, $method);
    }

    public function resource($regexpMany, $regexpOne, $controller)
    {
        $mapping = [
            'many' => [
                'GET' => 'index',
                'POST' => 'store',
            ],
            'one' => [
                'GET' => 'show',
                'PUT' => 'update',
                'DELETE' => 'destroy',
            ],
        ];
        foreach ($mapping as $type => $map) {
            foreach ($map as $requestMethod => $method) {
                $this->action(
                    $requestMethod,
                    ($type == 'many' ? $regexpMany : $regexpOne),
                    $controller,
                    $method
                );
            }
        }
    }

    private function action($requestMethod, $regexp, $controller, $method)
    {
        $this->routes[$regexp][$requestMethod] = [
            'controller' => $controller,
            'method' => $method,
        ];
        if ($this->before !== []) {
            $this->routes[$regexp][$requestMethod]['before'] = $this->before;
        }
    }

    public function before($callback, $controller, $method)
    {
        $this->before[] = [
            'controller' => $controller,
            'method' => $method,
        ];
        $callback($this);
        array_pop($this->before);
    }

    public function route()
    {
        foreach ($this->routes as $regexp => $routes) {
            if (preg_match($regexp, $this->request->getUri(), $params) === 1) {
                $route = $routes[$this->request->getMethod()] ?? null;
                if ($route === null) {
                    throw new Exception\MethodNotAllowed('Method not allowed');
                } else {
                    if (isset($route['before'])) {
                        foreach ($route['before'] as $routeBefore) {
                            $this->callController($routeBefore);
                        }
                    }
                    return $this->callController($route, $params);
                }
            }
        }

        throw new Exception\NotFound('Page not found');
    }

    private function callController(array $route, array $params = null)
    {
        $ctrlName = $route['controller'];
        $ctrl = new $ctrlName($this->request, $this->container);
        if (!is_a($ctrl, '\On2Media\Zeptowaf\Routable')) {
            throw new Exception\Exception('Controller isn\'t routable');
        }
        if (!method_exists($ctrl, $route['method'])) {
            throw new Exception\Exception('Method does not exist');
        }
        if ($params === null) {
            return $ctrl->{$route['method']}();
        }
        return $ctrl->{$route['method']}($params);
    }
}
