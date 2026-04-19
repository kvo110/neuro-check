<?php
// load session + shared helpers first
require_once __DIR__ . '/includes/bootstrap.php';

// quiz should only be available after login
requireLogin();

// pulling in the quiz question helper file
require_once __DIR__ . '/includes/quiz_data.php';

// actually load the questions from the helper function
$questions = getQuizQuestions();
$totalQuestions = count($questions);
$errors = [];

// set up default session values if they don't exist yet
if (!isset($_SESSION['score'])) {
  $_SESSION['score'] = 0;
}

if (!isset($_SESSION['question_index'])) {
  $_SESSION['question_index'] = 0;
}

if (!isset($_SESSION['answers'])) {
  $_SESSION['answers'] = [];
}

// current question position in the quiz
$currentIndex = (int) $_SESSION['question_index'];

// if the quiz was already completed somehow, send user to results
if ($currentIndex >= $totalQuestions) {
  header('Location: result.php');
  exit;
}

// handle answer submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // choice comes in as the selected answer text
  $selectedAnswer = trim((string) ($_POST['choice'] ?? ''));

  if ($selectedAnswer === '') {
    $errors['choice'] = 'Please choose an answer before continuing.';
  } else {
    $currentQuestion = $questions[$currentIndex];
    $correctAnswer = $currentQuestion['answer'];
    $isCorrect = $selectedAnswer === $correctAnswer;

    // storing full answer details so the result page can review everything later
    $_SESSION['answers'][] = [
      'question' => $currentQuestion['question'],
      'selected' => $selectedAnswer,
      'correct' => $correctAnswer,
      'is_correct' => $isCorrect
    ];

    // simple scoring for now
    if ($isCorrect) {
      $_SESSION['score'] += 10;
    }

    // move to next question
    $_SESSION['question_index']++;

    // once all questions are done, go to result page
    if ($_SESSION['question_index'] >= $totalQuestions) {
      header('Location: result.php');
      exit;
    }

    // reload quiz page so the next question shows up cleanly
    header('Location: quiz.php');
    exit;
  }
}

// refresh current values after any possible updates
$currentIndex = (int) $_SESSION['question_index'];
$currentQuestion = $questions[$currentIndex];
$questionNumber = $currentIndex + 1;
$progressPercent = (int) round(($questionNumber / $totalQuestions) * 100);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NeuroCheck | Quiz</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <div class="page-shell">
    <header class="site-header">
      <div class="brand-wrap">
        <div class="brand-badge">NC</div>
        <div>
          <h1 class="site-title">NeuroCheck</h1>
          <p class="site-subtitle">Quiz In Progress</p>
        </div>
      </div>

      <nav class="top-nav">
        <a href="index.php" class="nav-link">Home</a>
        <a href="dashboard.php" class="nav-link">Dashboard</a>
        <a href="quiz.php" class="nav-link active">Quiz</a>
        <a href="logout.php" class="nav-link">Logout</a>
      </nav>
    </header>

    <main class="quiz-layout">
      <section class="glow-card quiz-card">
        <p class="eyebrow">Question <?php echo $questionNumber; ?> of <?php echo $totalQuestions; ?></p>
        <h2 class="hero-title quiz-title"><?php echo e($currentQuestion['question']); ?></h2>

        <!-- little progress bar so the quiz feels more complete -->
        <div class="progress-block">
          <div class="progress-label-row">
            <span>Quiz Progress</span>
            <span><?php echo $progressPercent; ?>%</span>
          </div>
          <div class="progress-bar-shell">
            <div class="progress-bar-fill" style="width: <?php echo $progressPercent; ?>%;"></div>
          </div>
        </div>

        <div class="quiz-top-row">
          <div class="mini-panel">
            <span class="mini-label">Current Score</span>
            <span class="mini-value"><?php echo (int) $_SESSION['score']; ?></span>
          </div>

          <div class="mini-panel">
            <span class="mini-label">Logged In User</span>
            <span class="mini-value"><?php echo e($_SESSION['user']); ?></span>
          </div>
        </div>

        <?php if (!empty($errors['choice'])): ?>
          <div class="message-box error-box">
            <?php echo e($errors['choice']); ?>
          </div>
        <?php endif; ?>

        <form action="quiz.php" method="post" class="quiz-form">
          <div class="choice-list">
            <?php foreach ($currentQuestion['choices'] as $choice): ?>
              <label class="choice-card">
                <input
                  type="radio"
                  name="choice"
                  value="<?php echo e($choice); ?>"
                  class="choice-input"
                >
                <span class="choice-text"><?php echo e($choice); ?></span>
              </label>
            <?php endforeach; ?>
          </div>

          <button type="submit" class="btn btn-primary quiz-button">Submit Answer</button>
        </form>
      </section>
    </main>

    <footer class="site-footer">
      <p>NeuroCheck • CSC 4370 • Spring 2026</p>
    </footer>
  </div>

</body>
</html>
