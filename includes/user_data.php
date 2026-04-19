<?php
require_once __DIR__ . '/bootstrap.php';

// keeping the user file path in one place so register and login both use it
const USER_DATA_FILE = __DIR__ . '/../data/users.json';

// make sure the data folder and json file exist before reading/writing
function ensureUserDataFile(): bool {
  $dataDir = dirname(USER_DATA_FILE);

  // create the data folder if it somehow doesn't exist yet
  if (!is_dir($dataDir)) {
    if (!mkdir($dataDir, 0775, true) && !is_dir($dataDir)) {
      return false;
    }
  }

  // create the users file if it doesn't exist yet
  if (!file_exists(USER_DATA_FILE)) {
    if (file_put_contents(USER_DATA_FILE, "[]", LOCK_EX) === false) {
      return false;
    }
  }

  return true;
}

// read all users from the json file
function loadUsers(): array {
  if (!ensureUserDataFile()) {
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
  if (!ensureUserDataFile()) {
    return false;
  }

  $json = json_encode($users, JSON_PRETTY_PRINT);

  if ($json === false) {
    return false;
  }

  return file_put_contents(USER_DATA_FILE, $json, LOCK_EX) !== false;
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
