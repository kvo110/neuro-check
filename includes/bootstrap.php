<?php
// Start session if it hasn't already started
// This allows us to store user data across pages (login, score, etc.)
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Helper function to safely display user input
// Prevents XSS attacks by escaping special characters
function e(string $value): string {
  return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
