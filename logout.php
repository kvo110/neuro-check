<?php
require_once __DIR__ . '/includes/bootstrap.php';

// clearing session data out fully
$_SESSION = [];

// remove session cookie too just to keep logout clean
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

session_destroy();

header('Location: index.php');
exit;
