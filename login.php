<?php
// pulling in user functions + session start
require_once __DIR__ . '/includes/user_data.php';

// if already logged in, no need to stay here
if (isset($_SESSION['user'])) {
  header('Location: index.php');
  exit;
}

$username = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // grabbing form input
  $username = trim((string) $_POST['username']);
  $password = (string) $_POST['password'];

  // basic validation
  if ($username === '') {
    $errors['username'] = 'Username is required.';
  }

  if ($password === '') {
    $errors['password'] = 'Password is required.';
  }

  // only check credentials if inputs are valid
  if (empty($errors)) {
    if (verifyUserCredentials($username, $password)) {

      // save user into session
      $_SESSION['user'] = $username;

      // send them back to homepage (later we’ll use dashboard)
      header('Location: index.php');
      exit;

    } else {
      $errors['general'] = 'Invalid username or password.';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>NeuroCheck | Login</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="page-shell">

  <!-- Header -->
  <header class="site-header">
    <div class="brand-wrap">
      <div class="brand-badge">NC</div>
      <div>
        <h1 class="site-title">NeuroCheck</h1>
        <p class="site-subtitle">Login</p>
      </div>
    </div>

    <nav class="top-nav">
      <a href="index.php" class="nav-link">Home</a>
      <a href="login.php" class="nav-link active">Login</a>
      <a href="register.php" class="nav-link">Register</a>
      <a href="leaderboard.php" class="nav-link">Leaderboard</a>
    </nav>
  </header>

  <!-- Login form -->
  <main class="auth-layout">
    <section class="auth-card glow-card">

      <p class="eyebrow">Access Portal</p>
      <h2 class="hero-title auth-title">Login</h2>

      <?php if (!empty($errors['general'])): ?>
        <div class="message-box error-box">
          <?php echo e($errors['general']); ?>
        </div>
      <?php endif; ?>

      <form method="post" class="auth-form">

        <div class="form-group">
          <label class="form-label">Username</label>
          <input
            type="text"
            name="username"
            class="form-input"
            value="<?php echo e($username); ?>"
          >
          <?php if (!empty($errors['username'])): ?>
            <p class="form-error"><?php echo e($errors['username']); ?></p>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label class="form-label">Password</label>
          <input
            type="password"
            name="password"
            class="form-input"
          >
          <?php if (!empty($errors['password'])): ?>
            <p class="form-error"><?php echo e($errors['password']); ?></p>
          <?php endif; ?>
        </div>

        <button class="btn btn-primary auth-button">Login</button>

      </form>

      <p class="auth-switch">
        Don’t have an account?
        <a href="register.php" class="inline-link">Register</a>
      </p>

    </section>
  </main>

</div>

</body>
</html>
