<?php

namespace Common\Routing;

use DI\Container;

/**
 * Class Route 
 */
class Route
{

    use PathResolver;

    /**
     * Collect all routes
     *
     * @var array $routes Array of routes
     */
    private static array $routes = [];

    /**
     * PHP-DI (Dependency Injection) container
     * 
     * @var Container $container
     */
    protected static Container $container;

    /**
     * Set the container
     *
     * @param $container
     */
    public static function setContainer(Container $container): void
    {
        self::$container = $container;
    }

    /**
     * Add a route to the routes array
     *
     * @var string $method The HTTP method
     * @var string $route The route path
     * @var callable|array $handler The controller and method
     *
     */
    public static function addRoute(string $method, string $path, callable|array $handler): void
    {
        self::$routes[] = [
            'method' => $method,
            'handler' => $handler,
            'path' => $path
        ];
    }

    /**
     * Add a GET route
     *
     * @param string $route The route path
     * @param callable|array $handler The controller and method
     */
    public static function get(string $route, callable|array $handler): void
    {
        self::addRoute('GET', $route, $handler);
    }

    /**
     * Add a POST route
     *
     * @param string $route The route path
     * @param callable|array $handler The controller and method
     */
    public static function post(string $route, callable|array $controller): void
    {
        self::addRoute('POST', $route, $controller);
    }

    /**
     * Add a PUT route
     *
     * @param string $route The route path
     * @param callable|array $handler The controller and method
     */
    public static function put(string $route, callable|array $controller): void
    {
        self::addRoute('PUT', $route, $controller);
    }

    /**
     * Add a DELETE route
     *
     * @param string $route The route path
     * @param callable|array $handler The controller and method
     */
    public static function delete(string $route, callable|array $controller): void
    {
        self::addRoute('DELETE', $route, $controller);
    }

    /**
     * Get all routes
     *
     * @return array
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }

    public static function dispatch(string $method, string $uri): mixed
    {
        foreach (self::$routes as $route) {
            $params = self::matchPath($route['path'], $uri);
            if ($route['method'] === $method && $params !== false) {
                return self::handleRoute($route['handler'], $params);
            }
        }

        throw new \Exception('Route not found');
    }
}
