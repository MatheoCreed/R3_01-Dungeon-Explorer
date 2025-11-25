<?php

class CreationPersController {
    public function index() {

        require_once "models/ClassRepository.php";
        $repo = new ClassRepository;
        $classes = $repo->getAll(); // liste d'objets GameClass

        require_once "views/creationPers_view.php";
    }
}
