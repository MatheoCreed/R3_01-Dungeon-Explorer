<?php
require_once 'HeroRepository.php';

class HeroController {
    private $repo;

    public function __construct(HeroRepository $repo) {
        $this->repo = $repo;
    }

    public function showHero($id) {
        $hero = $this->repo->findById($id);

        if (!$hero) {
            die("Héros introuvable !");
        }

        // On passe $hero à la vue
        include 'views/pageuser.php';
    }
}
