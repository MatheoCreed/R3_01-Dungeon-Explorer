<?php


require_once __DIR__ . '/Database.php'; 

require_once __DIR__ . '/controllers/ChapterController.php';


$pdo = $db; 

$chapterController = new ChapterController($pdo);

$chapterId = isset($_GET['chapter']) ? (int)$_GET['chapter'] : 1;

// Traiter les actions POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'choose') {
        $next_chapter_id = (int)($_POST['next_chapter_id'] ?? 0);
        $hero_id = 1; // À récupérer depuis session plus tard
        
        // IMPORTANT : mettre à jour Hero_Progress pour lier le héros au chapitre suivant
        $stmt = $pdo->prepare('UPDATE Hero_Progress SET chapter_id = ? WHERE hero_id = ?');
        $stmt->execute([$next_chapter_id, $hero_id]);
        
        // Rediriger pour éviter re-soumission
        header('Location: index.php');
        exit;
    }
    if ($_POST['action'] === 'fight') {
        $chapter_id = (int)($_POST['chapter_id'] ?? 0);
        $hero_id = 1; // À récupérer depuis session plus tard

        // Rediriger vers la page de combat
        header('Location: index.php?action=combat&chapter=' . $chapter_id);
        exit;
    }
}

// Afficher le chapitre du héros
$hero_id = 1; // À récupérer depuis session

// Si demande d'afficher un combat
if (isset($_GET['action']) && $_GET['action'] === 'combat') {
    require_once __DIR__ . '/controllers/CombatController.php';
    $combatController = new CombatController($pdo);
    $chapterForCombat = isset($_GET['chapter']) ? (int)$_GET['chapter'] : $hero_id;
    $combatController->show($chapterForCombat);
    exit;
}

$chapterController->show($hero_id);
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

