<?php

class Router
{
    private array $routes = [];

    public function get(string $path, callable $action): void
    {
        $this->addRoute('GET', $path, $action);
    }

    public function post(string $path, callable $action): void
    {
        $this->addRoute('POST', $path, $action);
    }

    private function addRoute(string $method, string $path, callable $action): void
    {
        $path = $this->normalize($path);
        $this->routes[$method][$path] = $action;
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = $this->normalize($uri);

        if (!isset($this->routes[$method][$path])) {
            http_response_code(404);
            echo "<h1>404</h1>";
            echo "<p>La page demandée n'existe pas.</p>";
            return;
        }

        $action = $this->routes[$method][$path];
        $action();
    }

    private function normalize(string $path): string
    {
        $path = parse_url($path, PHP_URL_PATH) ?? '/';

        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

        if ($scriptDir !== '/' && $scriptDir !== '.' && strpos($path, $scriptDir) === 0) {
            $path = substr($path, strlen($scriptDir));
        }

        $path = '/' . trim($path, '/');

        return $path === '//' ? '/' : $path;
    }
}