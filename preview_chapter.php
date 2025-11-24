<?php
// preview_chapter.php — script simple pour visualiser la vue de chapitre

require_once __DIR__ . '/controllers/ChapterController.php';

$controller = new ChapterController();

// Récupère le paramètre ?chapter= dans l'URL, ou 1 par défaut
$chapterId = isset($_GET['chapter']) ? (int)$_GET['chapter'] : 1;

$controller->show($chapterId);
