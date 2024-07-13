<?php

namespace Common\Routing;

use ReflectionMethod, ReflectionFunction, ReflectionParameter;
use DI\Container;

trait CompileRoute
{

    protected static Container $container;

    protected static function matchPath(string $routePath, string $uri): array|false
    {
        $routeParts = explode('/', trim($routePath, '/'));
        $uriParts = explode('/', trim($uri, '/'));
        $params = [];

        if (count($routeParts) !== count($uriParts)) {
            return false;
        }

        foreach ($routeParts as $index => $routePart) {
            if (strpos($routePart, '{') === 0 && strpos($routePart, '}') === strlen($routePart) - 1) {
                $paramName = trim($routePart, '{}');
                $params[$paramName] = $uriParts[$index];
            } elseif ($routePart !== $uriParts[$index]) {
                return false;
            }
        }

        return $params;
    }

    private static function handleRoute($handler, array $params)
    {
        if (is_array($handler) && count($handler) === 2) {
            [$class, $method] = $handler;
            $reflection = new ReflectionMethod($class, $method);
            $instance = self::$container ? self::$container->get($class) : new $class();
        } elseif (is_callable($handler)) {
            $reflection = new ReflectionFunction($handler);
            $instance = null;
        } else {
            throw new \Exception('Invalid route handler');
        }

        $args = self::resolveMethodDependencies($reflection, $params);

        return $instance ? $reflection->invokeArgs($instance, $args) : $reflection->invokeArgs($args);
    }

    private static function resolveMethodDependencies(ReflectionMethod|ReflectionFunction $reflection, array $params): array
    {
        return array_map(function (ReflectionParameter $param) use ($params) {
            if (isset($params[$param->getName()])) {
                return $params[$param->getName()];
            }

            if ($param->getType() && !$param->getType()->isBuiltin()) {
                return self::$container ? self::$container->get($param->getType()->getName()) : null;
            }

            if ($param->isDefaultValueAvailable()) {
                return $param->getDefaultValue();
            }

            throw new \Exception("Unable to resolve method parameter: " . $param->getName());
        }, $reflection->getParameters());
    }
}
