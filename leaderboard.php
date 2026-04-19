<?php
require_once __DIR__ . '/includes/leaderboard_data.php';

$entries = getTopLeaderboardEntries(10);
$isLoggedIn = isset($_SESSION['user']) && $_SESSION['user'] !== '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NeuroCheck | Leaderboard</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <div class="page-shell">
    <header class="site-header">
      <div class="brand-wrap">
        <div class="brand-badge">NC</div>
        <div>
          <h1 class="site-title">NeuroCheck</h1>
          <p class="site-subtitle">Leaderboard</p>
        </div>
      </div>

      <nav class="top-nav">
        <a href="index.php" class="nav-link">Home</a>
        <?php if ($isLoggedIn): ?>
          <a href="dashboard.php" class="nav-link">Dashboard</a>
          <a href="leaderboard.php" class="nav-link active">Leaderboard</a>
          <a href="logout.php" class="nav-link">Logout</a>
        <?php else: ?>
          <a href="login.php" class="nav-link">Login</a>
          <a href="register.php" class="nav-link">Register</a>
          <a href="leaderboard.php" class="nav-link active">Leaderboard</a>
        <?php endif; ?>
      </nav>
    </header>

    <main class="leaderboard-layout">
      <section class="glow-card leaderboard-card">
        <p class="eyebrow">Top Scores</p>
        <h2 class="hero-title leaderboard-title">NeuroCheck Rankings</h2>
        <p class="hero-text leaderboard-text">
          These are the highest quiz scores saved so far. Scores are sorted from highest to lowest.
        </p>

        <?php if (empty($entries)): ?>
          <div class="message-box success-box">
            No scores have been saved yet. Finish a quiz round and come back here.
          </div>
        <?php else: ?>
          <div class="leaderboard-table-wrap">
            <table class="leaderboard-table">
              <thead>
                <tr>
                  <th>Rank</th>
                  <th>User</th>
                  <th>Score</th>
                  <th>Correct</th>
                  <th>Played At</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($entries as $index => $entry): ?>
                  <tr>
                    <td>#<?php echo $index + 1; ?></td>
                    <td><?php echo e($entry['username']); ?></td>
                    <td><?php echo (int) $entry['score']; ?></td>
                    <td><?php echo (int) $entry['correct']; ?> / <?php echo (int) $entry['total']; ?></td>
                    <td><?php echo e($entry['played_at']); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>

        <div class="leaderboard-actions">
          <?php if ($isLoggedIn): ?>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
          <?php else: ?>
            <a href="login.php" class="btn btn-primary">Login</a>
          <?php endif; ?>
        </div>
      </section>
    </main>
  </div>

</body>
</html>
