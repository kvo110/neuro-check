<?php
require_once __DIR__ . '/includes/user_data.php';

// if somebody is already logged in, no reason to register again
if (isset($_SESSION['user'])) {
  header('Location: dashboard.php');
  exit;
}

$username = '';
$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // grabbing the form values and trimming extra spaces
  $username = trim((string) filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW));
  $password = (string) filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
  $confirmPassword = (string) filter_input(INPUT_POST, 'confirm_password', FILTER_UNSAFE_RAW);

  // basic username checks
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

  // confirm password check
  if ($confirmPassword === '') {
    $errors['confirm_password'] = 'Please confirm your password.';
  } elseif ($password !== '' && $password !== $confirmPassword) {
    $errors['confirm_password'] = 'Passwords do not match.';
  }

  // if there are no errors, save the new user
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
  <div class="page-shell">
    <header class="site-header">
      <div class="brand-wrap">
        <div class="brand-badge">NC</div>
        <div>
          <h1 class="site-title">NeuroCheck</h1>
          <p class="site-subtitle">Create your account to enter the system</p>
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
            <input
              type="password"
              id="password"
              name="password"
              class="form-input"
              placeholder="Enter a password"
            >
            <?php if (!empty($errors['password'])): ?>
              <p class="form-error"><?php echo e($errors['password']); ?></p>
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input
              type="password"
              id="confirm_password"
              name="confirm_password"
              class="form-input"
              placeholder="Re-enter your password"
            >
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
  </div>
</body>
</html>
