<?php
session_start();

require_once __DIR__ . '/Database.php';
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

            $controller = new $controllerName(); // ✅ plus de paramètre
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
$router->addRoute('hero/delete', 'HeroController@delete');
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

$router->addRoute('admin/monster/index', 'AdminMonsterController@index');
$router->addRoute('admin/monster/create', 'AdminMonsterController@create');
$router->addRoute('admin/monster/store', 'AdminMonsterController@store');
$router->addRoute('admin/monster/edit', 'AdminMonsterController@edit');
$router->addRoute('admin/monster/update', 'AdminMonsterController@update');
$router->addRoute('admin/monster/delete', 'AdminMonsterController@delete');
$router->addRoute('admin/monster/chapters', 'AdminMonsterController@chapters');        // page gestion apparitions
$router->addRoute('admin/monster/chapters/add', 'AdminMonsterController@addChapter');  // ajouter apparition
$router->addRoute('admin/monster/chapters/delete', 'AdminMonsterController@deleteChapter'); // supprimer apparition

// (optionnel) gestion loot
$router->addRoute('admin/monster/loot', 'AdminMonsterController@loot');
$router->addRoute('admin/monster/loot/add', 'AdminMonsterController@addLoot');
$router->addRoute('admin/monster/loot/delete', 'AdminMonsterController@deleteLoot');

$router->addRoute('equipment', 'EquipmentController@index');
$router->addRoute('equipment/equip', 'EquipmentController@equip');
$router->addRoute('equipment/unequip', 'EquipmentController@unequip');

$router->addRoute('merchant', 'MerchantController@show');
$router->addRoute('merchant/buy', 'MerchantController@buy');

$router->addRoute("combat/show", "CombatController@show");



$router->route($_GET['url'] ?? '');
