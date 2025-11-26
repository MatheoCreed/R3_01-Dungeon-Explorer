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
}
