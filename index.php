<?php


require_once __DIR__ . '/Database.php'; 

require_once __DIR__ . '/controllers/ChapterController.php';


$pdo = $db; 

$chapterController = new ChapterController($pdo);

$chapterId = isset($_GET['chapter']) ? (int)$_GET['chapter'] : 1;

// --- Shortcut: bypass the router and go directly to the chapter view -------
// This will ignore the rest of the routing/login logic and immediately
// display the requested chapter. Keep the original router code below
// commented out so it can be restored later if needed.

$chapterController->show(1);
exit;

/*
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
        
        $url = trim($url, '/');

        if (isset($this->routes[$url])) {

            list($controllerName, $methodName) = explode('@', $this->routes[$url]);

            require_once "controllers/$controllerName.php";

            $controller = new $controllerName();
            $controller->$methodName();
            return;
        }

        require 'views/404.php';
    }
}

$router = new Router('R3_01-Dungeon-Explorer');


$router->addRoute('', 'AccueilController@index');

$router->route($_GET['url'] ?? '');
*/

?>

