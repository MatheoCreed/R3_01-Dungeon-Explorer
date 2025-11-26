<?php
// index.php - redirect directly to chapter_view.php

require_once __DIR__ . '/Database.php'; // ce fichier créé $db (PDO)

require_once __DIR__ . '/controllers/ChapterController.php';

$pdo = $db; // Database.php expose $db

$controller = new ChapterController($pdo);

// Récupère l'id du chapitre demandé (par défaut 1)
$chapterId = isset($_GET['chapter']) ? (int)$_GET['chapter'] : 1;
$controller->show($chapterId);

?>