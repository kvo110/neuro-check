<?php
require_once __DIR__ . '/bootstrap.php';

// keeping leaderboard storage separate from auth and quiz logic
// makes the project easier to grow later without mixing everything together
const LEADERBOARD_FILE = __DIR__ . '/../data/leaderboard.json';

// make sure the leaderboard file exists before trying to use it
function ensureLeaderboardFile(): bool {
  $dataDir = dirname(LEADERBOARD_FILE);

  if (!is_dir($dataDir)) {
    if (!mkdir($dataDir, 0775, true) && !is_dir($dataDir)) {
      return false;
    }
  }

  if (!file_exists(LEADERBOARD_FILE)) {
    if (file_put_contents(LEADERBOARD_FILE, "[]", LOCK_EX) === false) {
      return false;
    }
  }

  return true;
}

// load the saved leaderboard entries
function loadLeaderboard(): array {
  if (!ensureLeaderboardFile()) {
    return [];
  }

  $raw = file_get_contents(LEADERBOARD_FILE);

  if ($raw === false || trim($raw) === '') {
    return [];
  }

  $entries = json_decode($raw, true);

  return is_array($entries) ? $entries : [];
}

// save updated leaderboard entries
function saveLeaderboard(array $entries): bool {
  if (!ensureLeaderboardFile()) {
    return false;
  }

  $json = json_encode($entries, JSON_PRETTY_PRINT);

  if ($json === false) {
    return false;
  }

  return file_put_contents(LEADERBOARD_FILE, $json, LOCK_EX) !== false;
}

// add one new score entry and then sort everything
function addLeaderboardEntry(string $username, int $score, int $correct, int $total): bool {
  $entries = loadLeaderboard();

  $entries[] = [
    'username' => $username,
    'score' => $score,
    'correct' => $correct,
    'total' => $total,
    'played_at' => date('Y-m-d H:i:s')
  ];

  // highest score first
  usort($entries, function (array $a, array $b): int {
    return $b['score'] <=> $a['score'];
  });

  return saveLeaderboard($entries);
}

// return only the top scores so the page doesn't get too long
function getTopLeaderboardEntries(int $limit = 10): array {
  $entries = loadLeaderboard();
  return array_slice($entries, 0, $limit);
}

