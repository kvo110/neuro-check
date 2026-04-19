<?php
require_once __DIR__ . '/bootstrap.php';

// keeping the user file path in one place so both register and login use it
const USER_DATA_FILE = __DIR__ . '/../data/users.json';

// read all users from the json file
function loadUsers(): array {
  if (!file_exists(USER_DATA_FILE)) {
    return [];
  }

  $raw = file_get_contents(USER_DATA_FILE);

  if ($raw === false || trim($raw) === '') {
    return [];
  }

  $users = json_decode($raw, true);

  return is_array($users) ? $users : [];
}

// save all users back into the json file
function saveUsers(array $users): bool {
  $json = json_encode($users, JSON_PRETTY_PRINT);

  if ($json === false) {
    return false;
  }

  return file_put_contents(USER_DATA_FILE, $json) !== false;
}

// check if a username is already taken
function usernameExists(string $username): bool {
  $users = loadUsers();

  foreach ($users as $user) {
    if (
      isset($user['username']) &&
      strtolower($user['username']) === strtolower($username)
    ) {
      return true;
    }
  }

  return false;
}

// add a new user to the file
function createUser(string $username, string $password): bool {
  $users = loadUsers();

  $users[] = [
    'username' => $username,
    // hashing the password now so login is safer later too
    'password' => password_hash($password, PASSWORD_DEFAULT)
  ];

  return saveUsers($users);
}

// find one user by username
function findUserByUsername(string $username): ?array {
  $users = loadUsers();

  foreach ($users as $user) {
    if (
      isset($user['username']) &&
      strtolower($user['username']) === strtolower($username)
    ) {
      return $user;
    }
  }

  return null;
}

// compare login password against the saved hash
function verifyUserCredentials(string $username, string $password): bool {
  $user = findUserByUsername($username);

  if ($user === null || !isset($user['password'])) {
    return false;
  }

  return password_verify($password, $user['password']);
}
