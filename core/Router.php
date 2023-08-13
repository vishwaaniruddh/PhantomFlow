<?php

class Router {
    public $routes = [
        'GET' => [],
        'POST' => []
    ];

    public function define($routes) {
        $this->routes = $routes;
    }

    public function get($uri, $controller) {
        return $this->routes['GET'][$uri] = $controller;
    }

    public function post($uri, $controller) {
        return $this->routes['POST'][$uri] = $controller;
    }

    public function auth($uri) {
        $_SESSION['authenticated_routes'][] = $uri;
        return $this;
    }

    public function direct($uri, $requestType) {
        if (array_key_exists($uri, $this->routes[$requestType])) {
            if ($this->requiresAuthentication($uri)) {
                $this->before(); // Call the authentication check
            }

            return $this->callAction(
                ...explode('@', $this->routes[$requestType][$uri])
            );
        } else {
            echo 'No Routes Defined';
        }
    }

    protected function requiresAuthentication($uri) {
        // List of routes that require authentication
        $authenticatedRoutes = [
            '','index', 'about', 'users', 'logout','add_mis','view_mis'
        ];

        return in_array($uri, $authenticatedRoutes);
    }

    protected function callAction($controller, $action) {
        $controller = new $controller;
        if (!method_exists($controller, $action)) {
            throw new Exception("{$controller} doesn't have the method {$action}.");
        }
        return $controller->$action();
    }

    public function before() {
        if (!isset($_SESSION['SERVICE_username'])) {
            header("Location: /login");
            exit;
        }
    }
}
