<?php

require_once __DIR__ . '/Database.php'; 

require_once __DIR__ . '/controllers/ChapterController.php';

$pdo = $db; 

$chapterController = new ChapterController($pdo);

$chapterId = isset($_GET['chapter']) ? (int)$_GET['chapter'] : 1;
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
$router->addRoute('index', 'AccueilController@index');
$router->addRoute('connexion', 'ConnexionController@index');
$router->addRoute('inscriptions', 'InscriptionController@index');
$router->addRoute('gestionCompte', 'GestionCompteController@index');
$router->addRoute('creation', 'CreationPersController@index');
$router->addRoute('page-user', 'PageUserController@index');
$router->addRoute('admin', 'AdminController@index');
$router->addRoute('pageUser', 'PageUserController@index');
$router->addRoute('hero/create', 'HeroController@createPage');
$router->addRoute('hero/insert', 'HeroController@insert');
$router->addRoute('hero/show', 'HeroController@show');


$router->route($_GET['url'] ?? '');

?>

