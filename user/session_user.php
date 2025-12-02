<?php
// Central session helper: start session, require DB and load current user
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Require database
require_once __DIR__ . '/../Database.php';

$currentUser = null;

if (!empty($_SESSION['user_id'])) {
    try {
        $stmt = $db->prepare("SELECT id, username, 
            COALESCE(current_level, 1) AS current_level, 
            COALESCE(xp, 0) AS xp,
            COALESCE(is_admin, 0) AS is_admin
            FROM users_aria WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        $currentUser = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    } catch (PDOException $e) {
        // Don't expose DB errors to output; keep $currentUser null on error
        $currentUser = null;
    }
}

// $currentUser is available to including scripts (or null if not logged in)
