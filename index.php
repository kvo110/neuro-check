<?php
// Include shared setup (session + helper functions)
require_once __DIR__ . '/includes/bootstrap.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NeuroCheck | Home</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <!-- Main container for layout -->
  <div class="page-shell">

    <!-- Header section (logo + navigation) -->
    <header class="site-header">

      <!-- Branding -->
      <div class="brand-wrap">
        <div class="brand-badge">NC</div>
        <div>
          <h1 class="site-title">NeuroCheck</h1>
          <p class="site-subtitle">PHP Quiz Game</p>
        </div>
      </div>

      <!-- Navigation menu -->
      <nav class="top-nav">
        <a href="index.php" class="nav-link active">Home</a>
        <a href="login.php" class="nav-link">Login</a>
        <a href="register.php" class="nav-link">Register</a>
        <a href="leaderboard.php" class="nav-link">Leaderboard</a>
      </nav>

    </header>

    <!-- Main content -->
    <main class="hero-grid">

      <!-- Left side: main info -->
      <section class="hero-card glow-card">

        <h2 class="hero-title">Test your knowledge with NeuroCheck</h2>

        <p class="hero-text">
          This project allows users to register, log in, and take a quiz.
          PHP handles all logic including sessions, scoring, and leaderboard tracking.
        </p>

        <!-- Action buttons -->
        <div class="hero-actions">
          <a href="register.php" class="btn btn-primary">Create Account</a>
          <a href="login.php" class="btn btn-secondary">Login</a>
        </div>

        <!-- Session info display -->
        <div class="status-row">

          <!-- Show current user -->
          <div class="mini-panel">
            <span class="mini-label">Current User</span>
            <span class="mini-value">
              <?php
              // If user is logged in, display username
              // Otherwise show "Guest"
              echo isset($_SESSION['user']) ? e($_SESSION['user']) : 'Guest';
              ?>
            </span>
          </div>

          <!-- Show session status -->
          <div class="mini-panel">
            <span class="mini-label">Session Status</span>
            <span class="mini-value">
              <?php
              // Check if PHP session is active
              echo session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Inactive';
              ?>
            </span>
          </div>

        </div>

      </section>

      <!-- Right side: feature list -->
      <aside class="info-card glow-card">

        <h3>Features</h3>

        <ul class="feature-list">
          <li>User login and registration</li>
          <li>PHP session tracking</li>
          <li>Quiz scoring system</li>
          <li>Leaderboard display</li>
        </ul>

      </aside>

    </main>

  </div>

</body>
</html>
