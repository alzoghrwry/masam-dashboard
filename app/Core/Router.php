<?php
namespace App\Core;

class Router {
    private array $routes = [];
    private $notFound = null;

    public function get(string $path, $handler) { $this->addRoute('GET', $path, $handler); }
    public function post(string $path, $handler) { $this->addRoute('POST', $path, $handler); }
    public function put(string $path, $handler) { $this->addRoute('PUT', $path, $handler); }
    public function delete(string $path, $handler) { $this->addRoute('DELETE', $path, $handler); }
    public function patch(string $path, $handler) { $this->addRoute('PATCH', $path, $handler); }

    private function addRoute(string $method, string $path, $handler) {
        $pattern = preg_replace('#\{([^}]+)\}#', '([^/]+)', $path);
        $pattern = "#^$pattern$#";
        $this->routes[] = ['method'=>$method,'pattern'=>$pattern,'handler'=>$handler];
    }

    public function setNotFound($handler) {
        $this->notFound = $handler;
    }

    public function dispatch(string $uri, string $method) {
        $path = parse_url($uri, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $path, $matches)) {
                array_shift($matches);
                $handler = $route['handler'];
                if (is_array($handler)) {
                    [$class, $action] = $handler;
                    $controller = new $class();
                    return call_user_func_array([$controller, $action], $matches);
                } elseif (is_callable($handler)) {
                    return call_user_func_array($handler, $matches);
                }
            }
        }

        // Not Found
        if ($this->notFound) {
            if (is_array($this->notFound)) {
                [$class, $action] = $this->notFound;
                $controller = new $class();
                return call_user_func_array([$controller, $action], []);
            } elseif (is_callable($this->notFound)) {
                return call_user_func($this->notFound);
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
