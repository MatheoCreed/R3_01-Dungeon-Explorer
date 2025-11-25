<?php
require 'autoload.php';

class Router
{
    private $routes = [];
    private $prefix;

    public function __construct($prefix = '')
    {
        $this->prefix = trim($prefix, '/');
    }

    public function addRoute($uri, $controllerMethod)
    {
        $this->routes[trim($uri, '/')] = $controllerMethod;
    }

    public function route($url)
    {
        // Nettoyage
        $url = trim($url, '/');

        // Si route trouvée
        if (isset($this->routes[$url])) {

            list($controllerName, $methodName) = explode('@', $this->routes[$url]);

            require_once "controllers/$controllerName.php";

            $controller = new $controllerName();
            $controller->$methodName();
            return;
        }

        // Sinon 404
        require 'views/404.php';
    }
}

// ROUTES
$router = new Router('R3_01-Dungeon-Explorer');


$router->addRoute('', 'AccueilController@index');
$router->addRoute('creation', 'CreationPersController@index');

// DISPATCH
$router->route($_GET['url'] ?? '');
