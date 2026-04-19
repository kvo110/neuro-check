<?php
require_once __DIR__ . '/includes/bootstrap.php';

// clearing everything out so the user is fully logged out
$_SESSION = [];

// if the session cookie exists, remove it too
if (ini_get('session.use_cookies')) {
  $params = session_get_cookie_params();

  setcookie(
    session_name(),
    '',
    time() - 42000,
    $params['path'],
    $params['domain'],
    $params['secure'],
    $params['httponly']
  );
}

// end the session and go back home
session_destroy();

header('Location: index.php');
exit;
