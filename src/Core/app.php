<?php

namespace Core;

use Common\Routing\Route;
use DI\ContainerBuilder;
use DI\Container;

class App
{
    private Container $container;

    public function __construct()
    {
        $this->bootContainer();
        $this->registerServices();
        $this->load();
    }

    public static function init()
    {
        new self();
    }

    private function bootContainer()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAutowiring(true);
        $containerBuilder->addDefinitions(__DIR__ . '/../../config/container.php');
        $this->container = $containerBuilder->build();
    }

    private function registerServices(): void
    {
        $this->container->set(Container::class, $this->container);

        // Register the container in the Route class
        Route::setContainer($this->container);
    }

    private function load()
    {
        require_once __DIR__ . '/../../config/routes/web.php';
        require_once __DIR__ . '/../../config/routes/api.php';

        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $uri = $_SERVER['REQUEST_URI'];
            Route::dispatch(method: $method, uri: $uri);
        } catch (\Exception $e) {
            http_response_code(404);
            echo '<div style="display: grid; place-items: center; height: 100%; font-family: Segoe UI, sans-serif;opacity: .7"> <h2>404 Not Found</h2> </div>';
        }
    }
}
