<?php
session_start();

require_once __DIR__ . '/Database.php'; 

require_once __DIR__ . '/controllers/ChapterController.php';

$pdo = $db; 

$chapterController = new ChapterController();

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
$router->addRoute('admin/chapter/index', 'ChapterAdminController@index');
$router->addRoute('admin/chapter/create', 'ChapterAdminController@create');
$router->addRoute('admin/chapter/edit', 'ChapterAdminController@edit');
$router->addRoute('admin/chapter/delete', 'ChapterAdminController@delete');
$router->addRoute('admin/link/index', 'ChapterLinkController@index');
$router->addRoute('admin/link/create1', 'ChapterLinkController@create1');
$router->addRoute('admin/link/create', 'ChapterLinkController@create');
$router->addRoute('admin/link/edit', 'ChapterLinkController@edit');
$router->addRoute('admin/link/delete', 'ChapterLinkController@delete');
$router->addRoute('chapter/show', 'ChapterController@show');
$router->addRoute('next', 'ChapterController@nextChapter');
$router->addRoute('admin/user/index', 'AdminUserController@index');
$router->addRoute('admin/user/delete', 'AdminUserController@delete');

$router->addRoute("admin/class/index", "AdminClassController@index");
$router->addRoute("admin/class/create", "AdminClassController@create");
$router->addRoute("admin/class/store", "AdminClassController@store");
$router->addRoute("admin/class/edit", "AdminClassController@edit");
$router->addRoute("admin/class/update", "AdminClassController@update");
$router->addRoute("admin/class/delete", "AdminClassController@delete");




$router->route($_GET['url'] ?? '');

?>

