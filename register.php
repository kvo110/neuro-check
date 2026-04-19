<?php
require_once __DIR__ . '/includes/user_data.php';

// if somebody is already logged in, no reason to stay on register
if (isset($_SESSION['user'])) {
  header('Location: dashboard.php');
  exit;
}

$username = '';
$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // grabbing form values and trimming the username
  $username = trim((string) ($_POST['username'] ?? ''));
  $password = (string) ($_POST['password'] ?? '');
  $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

  // username checks
  if ($username === '') {
    $errors['username'] = 'Please enter a username.';
  } elseif (strlen($username) < 3) {
    $errors['username'] = 'Username must be at least 3 characters long.';
  } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $errors['username'] = 'Username can only contain letters, numbers, and underscores.';
  } elseif (usernameExists($username)) {
    $errors['username'] = 'That username is already taken.';
  }

  // password checks
  if ($password === '') {
    $errors['password'] = 'Please enter a password.';
  } elseif (strlen($password) < 6) {
    $errors['password'] = 'Password must be at least 6 characters long.';
  }

  // confirm password checks
  if ($confirmPassword === '') {
    $errors['confirm_password'] = 'Please confirm your password.';
  } elseif ($password !== '' && $password !== $confirmPassword) {
    $errors['confirm_password'] = 'Passwords do not match.';
  }

  // if everything looks good, save the account
  if (empty($errors)) {
    if (createUser($username, $password)) {
      $successMessage = 'Account created successfully. You can log in now.';
      $username = '';
    } else {
      $errors['general'] = 'Something went wrong while saving your account.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NeuroCheck | Register</title>
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
          <p class="site-subtitle">Create your account</p>
        </div>
      </div>

      <nav class="top-nav">
        <a href="index.php" class="nav-link">Home</a>
        <a href="login.php" class="nav-link">Login</a>
        <a href="register.php" class="nav-link active">Register</a>
        <a href="leaderboard.php" class="nav-link">Leaderboard</a>
      </nav>
    </header>

    <main class="auth-layout">
      <section class="auth-card glow-card">
        <p class="eyebrow">Account Setup</p>
        <h2 class="hero-title auth-title">Register for NeuroCheck</h2>
        <p class="hero-text auth-text">
          Make an account first, then log in and start working through the quiz system.
        </p>

        <?php if (!empty($errors['general'])): ?>
          <div class="message-box error-box">
            <?php echo e($errors['general']); ?>
          </div>
        <?php endif; ?>

        <?php if ($successMessage !== ''): ?>
          <div class="message-box success-box">
            <?php echo e($successMessage); ?>
          </div>
        <?php endif; ?>

        <form action="register.php" method="post" class="auth-form" novalidate>
          <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input
              type="text"
              id="username"
              name="username"
              class="form-input"
              value="<?php echo e($username); ?>"
              placeholder="Enter a username"
            >
            <?php if (!empty($errors['username'])): ?>
              <p class="form-error"><?php echo e($errors['username']); ?></p>
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label for="password" class="form-label">Password</label>

            <!-- password field wrapper makes room for the visibility icon -->
            <div class="password-field">
              <input
                type="password"
                id="password"
                name="password"
                class="form-input password-input"
                placeholder="Enter a password"
              >
              <button
                type="button"
                class="password-toggle"
                data-target="password"
                aria-label="Show password"
                aria-pressed="false"
              >
                👁
              </button>
            </div>

            <?php if (!empty($errors['password'])): ?>
              <p class="form-error"><?php echo e($errors['password']); ?></p>
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label for="confirm_password" class="form-label">Confirm Password</label>

            <!-- using the same toggle pattern here so the two password fields match -->
            <div class="password-field">
              <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                class="form-input password-input"
                placeholder="Re-enter your password"
              >
              <button
                type="button"
                class="password-toggle"
                data-target="confirm_password"
                aria-label="Show password"
                aria-pressed="false"
              >
                👁
              </button>
            </div>

            <?php if (!empty($errors['confirm_password'])): ?>
              <p class="form-error"><?php echo e($errors['confirm_password']); ?></p>
            <?php endif; ?>
          </div>

          <button type="submit" class="btn btn-primary auth-button">Create Account</button>
        </form>

        <p class="auth-switch">
          Already have an account?
          <a href="login.php" class="inline-link">Login here</a>
        </p>
      </section>
    </main>

    <footer class="site-footer">
      <p>NeuroCheck • CSC 4370 • Spring 2026</p>
    </footer>
  </div>

  <script>
    // lets users toggle password visibility on both password fields
    document.querySelectorAll('.password-toggle').forEach(button => {
      button.addEventListener('click', () => {
        const targetId = button.getAttribute('data-target');
        const input = document.getElementById(targetId);

        if (!input) {
          return;
        }

        const showing = input.type === 'text';
        input.type = showing ? 'password' : 'text';
        button.setAttribute('aria-pressed', showing ? 'false' : 'true');
        button.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
        button.textContent = showing ? '👁' : '🙈';
      });
    });
  </script>

</body>
</html>
