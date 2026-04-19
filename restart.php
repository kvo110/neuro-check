<?php
require_once __DIR__ . '/includes/bootstrap.php';

// resetting quiz progress but keeping the user logged in
$_SESSION['score'] = 0;
$_SESSION['question_index'] = 0;
$_SESSION['answers'] = [];

// this matters so the next completed attempt can save to leaderboard again
$_SESSION['result_saved'] = false;

header('Location: quiz.php');
exit;
