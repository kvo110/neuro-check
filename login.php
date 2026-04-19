<?php
require_once __DIR__ . '/includes/user_data.php';

// if already logged in, just send them to the dashboard
if (isset($_SESSION['user'])) {
  header('Location: dashboard.php');
  exit;
}

$username = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // grabbing form input
  $username = trim((string) ($_POST['username'] ?? ''));
  $password = (string) ($_POST['password'] ?? '');

  // basic validation first
  if ($username === '') {
    $errors['username'] = 'Please enter your username.';
  }

  if ($password === '') {
    $errors['password'] = 'Please enter your password.';
  }

  // only try auth if the form was filled in
  if (empty($errors)) {
    if (verifyUserCredentials($username, $password)) {
      $_SESSION['user'] = $username;

      // setting these now so later quiz pages already have defaults
      if (!isset($_SESSION['score'])) {
        $_SESSION['score'] = 0;
      }

      if (!isset($_SESSION['question_index'])) {
        $_SESSION['question_index'] = 0;
      }

      if (!isset($_SESSION['answers'])) {
        $_SESSION['answers'] = [];
      }

      header('Location: dashboard.php');
      exit;
    } else {
      // keeping this general is a little safer
      $errors['general'] = 'Invalid username or password.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NeuroCheck | Login</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <!-- background layer for the animated neuro lightning effect -->
  <div class="neuro-lightning">
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
          <p class="site-subtitle">Log in and enter the system</p>
        </div>
      </div>

      <nav class="top-nav">
        <a href="index.php" class="nav-link">Home</a>
        <a href="login.php" class="nav-link active">Login</a>
        <a href="register.php" class="nav-link">Register</a>
        <a href="leaderboard.php" class="nav-link">Leaderboard</a>
      </nav>
    </header>

    <main class="auth-layout">
      <section class="auth-card glow-card">
        <p class="eyebrow">Access Portal</p>
        <h2 class="hero-title auth-title">Login to NeuroCheck</h2>
        <p class="hero-text auth-text">
          Enter your account info below to continue into the quiz dashboard.
        </p>

        <?php if (!empty($errors['general'])): ?>
          <div class="message-box error-box">
            <?php echo e($errors['general']); ?>
          </div>
        <?php endif; ?>

        <form action="login.php" method="post" class="auth-form" novalidate>
          <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input
              type="text"
              id="username"
              name="username"
              class="form-input"
              value="<?php echo e($username); ?>"
              placeholder="Enter your username"
            >
            <?php if (!empty($errors['username'])): ?>
              <p class="form-error"><?php echo e($errors['username']); ?></p>
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label for="password" class="form-label">Password</label>

            <!-- putting the visibility toggle inside the password field wrapper -->
            <div class="password-field">
              <input
                type="password"
                id="password"
                name="password"
                class="form-input password-input"
                placeholder="Enter your password"
              >

              <button
                type="button"
                class="password-toggle"
                data-target="password"
                aria-label="Show password"
                aria-pressed="false"
              >
                <svg class="eye-icon eye-open" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/>
                  <circle cx="12" cy="12" r="3.2"/>
                </svg>

                <svg class="eye-icon eye-closed" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M3 3l18 18"/>
                  <path d="M10.6 6.3A11.4 11.4 0 0 1 12 6c6.5 0 10 6 10 6a18.2 18.2 0 0 1-3.2 3.7"/>
                  <path d="M6.7 6.7A18.6 18.6 0 0 0 2 12s3.5 6 10 6a10.8 10.8 0 0 0 5.3-1.3"/>
                  <path d="M9.9 9.9A3 3 0 0 0 9 12a3 3 0 0 0 4.1 2.8"/>
                </svg>
              </button>
            </div>

            <?php if (!empty($errors['password'])): ?>
              <p class="form-error"><?php echo e($errors['password']); ?></p>
            <?php endif; ?>
          </div>

          <button type="submit" class="btn btn-primary auth-button">Login</button>
        </form>

        <p class="auth-switch">
          Need an account?
          <a href="register.php" class="inline-link">Register here</a>
        </p>
      </section>
    </main>

    <footer class="site-footer">
      <p>NeuroCheck • CSC 4370 • Spring 2026</p>
    </footer>
  </div>

  <script>
    // switching between hidden and visible password while swapping the icon state too
    document.querySelectorAll('.password-toggle').forEach(button => {
      button.addEventListener('click', () => {
        const input = document.getElementById(button.dataset.target);

        if (!input) {
          return;
        }

        const showing = input.type === 'text';
        input.type = showing ? 'password' : 'text';

        button.classList.toggle('is-visible', !showing);
        button.setAttribute('aria-pressed', showing ? 'false' : 'true');
        button.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
      });
    });
  </script>

</body>
</html>
