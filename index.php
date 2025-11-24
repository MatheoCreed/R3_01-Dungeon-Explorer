<?php
// index.php - redirect directly to chapter_view.php
header('Location: views/accueil_view.php', true, 302);
exit;

// index.php

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
        // Suppression du préfixe du début de l'URL
        if ($this->prefix && strpos($url, $this->prefix) === 0) {
            $url = substr($url, strlen($this->prefix) + 1);
        }

        // Suppression des barres obliques en trop
        $url = trim($url, '/');

        // Vérification de la correspondance de l'URL à une route définie
        if (array_key_exists($url, $this->routes)) {
            // Extraction du nom du contrôleur et de la méthode
            list($controllerName, $methodName) = explode('@', $this->routes[$url]);

            // Instanciation du contrôleur et appel de la méthode
            $controller = new $controllerName();
            $controller->$methodName();
        } else {
            // Route non trouvée, afficher une page 404
            include 'views/404.php';
        }
    }
}

// -----------------------------
// ROUTES
// -----------------------------
$router = new Router('/dungeonExplorer/R3_01-Dungeon-Explorer');

// Route accueil
$router->addRoute('chapter_view', 'ChapterController@index');

// -----------------------------
// DISPATCH
// -----------------------------
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->route($url);

?>