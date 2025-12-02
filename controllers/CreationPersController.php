<?php

class CreationPersController {
    public function index() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        require_once __DIR__ . "/../models/ClassRepository.php"; 
        $repo = new ClassRepository();
        $classesObj = $repo->getAll();

        $classes = [];
        if (is_array($classesObj)) {
            foreach ($classesObj as $class) {
                if (!is_object($class)) continue;
                $classes[] = [
                    "name" => $class->getName(),
                    "desc" => $class->getDescription(),
                    "pv"   => $class->getBasePv(),
                    "mana" => $class->getBaseMana(),
                    "str"  => $class->getStrength(),
                    "ini"  => $class->getInitiative(),
                    "max"  => $class->getMaxItems(),
                    "img"  => $class->getImage()
                ];
            }
        }

        require __DIR__ . "/../views/creationPers_view.php"; 
    }

public function create() {
    session_start();

    header("Content-Type: application/json");

    // Vérifier connexion utilisateur
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["error" => "Utilisateur non connecté"]);
        return;
    }
    $userId = $_SESSION['user_id'];

    // Récupération du JSON envoyé par fetch()
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || empty($data['name']) || empty($data['classId'])) {
        echo json_encode(["error" => "Paramètres manquants"]);
        return;
    }

    $heroName = trim($data['name']);
    $classId = (int)$data['classId'];

    // Charger la classe choisie
    require_once __DIR__ . "/../models/ClassRepository.php";
    $classRepo = new ClassRepository();
    $class = $classRepo->getById($classId);

    if (!$class) {
        echo json_encode(["error" => "Classe inconnue"]);
        return;
    }

    // Calcul des stats de base
    $pv   = $class->getBasePv();
    $mana = $class->getBaseMana();
    $str  = $class->getStrength();
    $ini  = $class->getInitiative();
    $img  = $class->getImage();

    // Création du héros
    require_once __DIR__ . "/../models/HeroRepository.php";
    $heroRepo = new HeroRepository();

    $success = $heroRepo->createHero(
        $heroName,
        $classId,
        $img,
        $pv,
        $mana,
        $str,
        $ini,
        $userId
    );

    if ($success) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Erreur lors de la création du personnage"]);
    }
}
}