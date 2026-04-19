<?php
// starting the session here keeps it shared across every page
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// small helper so user input prints safely in html
function e(string $value): string {
  return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// use this on protected pages later so guests get redirected out
function requireLogin(): void {
  if (!isset($_SESSION['user']) || $_SESSION['user'] === '') {
    header('Location: login.php');
    exit;
  }
}
