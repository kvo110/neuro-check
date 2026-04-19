<?php
// start session once here so every page can safely use session data
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// small helper for safe output in html
function e(string $value): string {
  return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// use this on pages that should only be visible after login
function requireLogin(): void {
  if (!isset($_SESSION['user']) || $_SESSION['user'] === '') {
    header('Location: login.php');
    exit;
  }
}
