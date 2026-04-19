<?php
// dashboard is a protected page, so guests should not be able to open it
require_once __DIR__ . '/includes/bootstrap.php';
requireLogin();

// setting defaults here just in case these were not created yet
if (!isset($_SESSION['score'])) {
  $_SESSION['score'] = 0;
}

if (!isset($_SESSION['question_index'])) {
  $_SESSION['question_index'] = 0;
}

if (!isset($_SESSION['answers'])) {
  $_SESSION['answers'] = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NeuroCheck | Dashboard</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <div class="page-shell">

    <header class="site-header">
      <div class="brand-wrap">
        <div class="brand-badge">NC</div>
        <div>
          <h1 class="site-title">NeuroCheck</h1>
          <p class="site-subtitle">Dashboard</p>
        </div>
      </div>

      <nav class="top-nav">
        <a href="index.php" class="nav-link">Home</a>
        <a href="dashboard.php" class="nav-link active">Dashboard</a>
        <a href="leaderboard.php" class="nav-link">Leaderboard</a>
        <a href="logout.php" class="nav-link">Logout</a>
      </nav>
    </header>

    <main class="dashboard-grid">

      <section class="glow-card dashboard-card">
        <p class="eyebrow">Welcome Back</p>
        <h2 class="hero-title dashboard-title">
          Hello, <?php echo e($_SESSION['user']); ?>
        </h2>

        <p class="hero-text">
          Your account is active and the system is ready. From here, you’ll be able
          to start the quiz, track your progress, and view your score as the project grows.
        </p>

        <div class="dashboard-actions">
          <!-- quiz.php does not exist yet, but this keeps our flow ready -->
          <a href="quiz.php" class="btn btn-primary">Start Quiz</a>
          <a href="leaderboard.php" class="btn btn-secondary">View Leaderboard</a>
        </div>
      </section>

      <aside class="glow-card dashboard-side-card">
        <h3 class="info-title">Current Session</h3>

        <div class="dashboard-stats">
          <div class="mini-panel">
            <span class="mini-label">Logged In User</span>
            <span class="mini-value"><?php echo e($_SESSION['user']); ?></span>
          </div>

          <div class="mini-panel">
            <span class="mini-label">Current Score</span>
            <span class="mini-value"><?php echo (int) $_SESSION['score']; ?></span>
          </div>

          <div class="mini-panel">
            <span class="mini-label">Question Index</span>
            <span class="mini-value"><?php echo (int) $_SESSION['question_index']; ?></span>
          </div>

          <div class="mini-panel">
            <span class="mini-label">Answers Saved</span>
            <span class="mini-value"><?php echo count($_SESSION['answers']); ?></span>
          </div>
        </div>

        <div class="callout-box">
          This page is protected with a session check, so guests should get sent back
          to the login page instead of seeing this content.
        </div>
      </aside>

    </main>

  </div>

</body>
</html>
