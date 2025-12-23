<?php

session_start();

require_once __DIR__ . '/Database.php'; 

require_once __DIR__ . '/controllers/ChapterController.php';
require_once __DIR__ . '/controllers/CombatController.php';


$pdo = $db; 

$chapterController = new ChapterController();
$combatController = new CombatController($pdo);

$chapterId = isset($_GET['chapter']) ? (int)$_GET['chapter'] : 1;

// Traiter les actions POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'choose') {
        $next_chapter_id = (int)($_POST['next_chapter_id'] ?? 0);
        $hero_id = 2; // À récupérer depuis session plus tard
        
        // IMPORTANT : mettre à jour Hero_Progress pour lier le héros au chapitre suivant
        $stmt = $pdo->prepare('UPDATE Hero_Progress SET chapter_id = ? WHERE hero_id = ?');
        $stmt->execute([$next_chapter_id, $hero_id]);
        
        // Rediriger pour éviter re-soumission
        header('Location: index.php');
        exit;
    }
    if ($_POST['action'] === 'fight') {
        $chapter_id = (int)($_POST['chapter_id'] ?? 0);
        $hero_id = 2; // À récupérer depuis session plus tard

        // Rediriger vers la page de combat
        header('Location: index.php?action=combat&chapter=' . $chapter_id);
        exit;
    }
}

// Afficher le chapitre du héros
$_SESSION['hero_id'] = 2;
$hero_id = $_SESSION['hero_id']; // À récupérer depuis session

// Si demande d'afficher un combat
if (isset($_GET['action']) && $_GET['action'] === 'combat') {
    
    $chapterForCombat = isset($_GET['chapter']) ? (int)$_GET['chapter'] : $hero_id;
    $combatController->show($chapterForCombat);
    exit;
}

$chapterController->show($hero_id);
exit;


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

