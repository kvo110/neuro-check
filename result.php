<?php
require_once __DIR__ . '/includes/leaderboard_data.php';
requireLogin();

// if there are no saved answers yet, results page shouldn't open
if (!isset($_SESSION['answers']) || !is_array($_SESSION['answers']) || count($_SESSION['answers']) === 0) {
  header('Location: dashboard.php');
  exit;
}

$answers = $_SESSION['answers'];
$score = (int) ($_SESSION['score'] ?? 0);
$totalQuestions = count($answers);
$correctCount = 0;

foreach ($answers as $answer) {
  if (!empty($answer['is_correct'])) {
    $correctCount++;
  }
}

// only save the score once per quiz attempt
if (empty($_SESSION['result_saved'])) {
  addLeaderboardEntry($_SESSION['user'], $score, $correctCount, $totalQuestions);
  $_SESSION['result_saved'] = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NeuroCheck | Results</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <!-- subtle neural lightning layer for the site background -->
  <div class="neuro-lightning" aria-hidden="true">
    <div class="neuro-bolt"></div>
    <div class="neuro-bolt"></div>
    <div class="neuro-bolt"></div>
    <div class="neuro-bolt"></div>
  </div>

  <div class="page-shell">
    <header class="site-header">
      <div class="brand-wrap">
        <div class="brand-badge">NC</div>
        <div>
          <h1 class="site-title">NeuroCheck</h1>
          <p class="site-subtitle">Quiz Results</p>
        </div>
      </div>

      <nav class="top-nav">
        <a href="index.php" class="nav-link">Home</a>
        <a href="dashboard.php" class="nav-link">Dashboard</a>
        <a href="leaderboard.php" class="nav-link">Leaderboard</a>
        <a href="result.php" class="nav-link active">Results</a>
        <a href="logout.php" class="nav-link">Logout</a>
      </nav>
    </header>

    <main class="result-layout">
      <section class="glow-card result-card">
        <p class="eyebrow">Quiz Complete</p>
        <h2 class="hero-title result-title">Nice work, <?php echo e($_SESSION['user']); ?></h2>

        <div class="result-summary">
          <div class="mini-panel">
            <span class="mini-label">Final Score</span>
            <span class="mini-value"><?php echo $score; ?></span>
          </div>

          <div class="mini-panel">
            <span class="mini-label">Correct Answers</span>
            <span class="mini-value"><?php echo $correctCount; ?> / <?php echo $totalQuestions; ?></span>
          </div>
        </div>

        <div class="answer-review">
          <?php foreach ($answers as $index => $answer): ?>
            <div class="review-card <?php echo !empty($answer['is_correct']) ? 'review-correct' : 'review-wrong'; ?>">
              <p class="review-question">
                <?php echo ($index + 1) . '. ' . e($answer['question']); ?>
              </p>
              <p class="review-line">
                Your answer: <strong><?php echo e($answer['selected']); ?></strong>
              </p>
              <p class="review-line">
                Correct answer: <strong><?php echo e($answer['correct']); ?></strong>
              </p>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="result-actions">
          <a href="restart.php" class="btn btn-primary">Play Again</a>
          <a href="leaderboard.php" class="btn btn-secondary">View Leaderboard</a>
        </div>
      </section>
    </main>
  </div>

</body>
</html>
