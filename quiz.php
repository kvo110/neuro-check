<?php
require_once __DIR__ . '/includes/quiz_data.php';
requireLogin();

// loading the questions from the helper file
$questions = getQuizQuestions();

// if the session values aren't there yet, set them up now
if (!isset($_SESSION['score'])) {
  $_SESSION['score'] = 0;
}

if (!isset($_SESSION['question_index'])) {
  $_SESSION['question_index'] = 0;
}

if (!isset($_SESSION['answers'])) {
  $_SESSION['answers'] = [];
}

$currentIndex = (int) $_SESSION['question_index'];
$totalQuestions = count($questions);
$errors = [];

// if somehow the quiz index already passed the question count,
// just send the user to the results page
if ($currentIndex >= $totalQuestions) {
  header('Location: result.php');
  exit;
}

// handle answer submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $selectedAnswer = trim((string) ($_POST['answer'] ?? ''));

  if ($selectedAnswer === '') {
    $errors['answer'] = 'Please choose an answer before continuing.';
  } else {
    $currentQuestion = $questions[$currentIndex];
    $correctAnswer = $currentQuestion['answer'];
    $isCorrect = $selectedAnswer === $correctAnswer;

    // saving each answered question so the result page can show a summary later
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

    // move to the next question
    $_SESSION['question_index']++;

    // if quiz is done, go to results
    if ($_SESSION['question_index'] >= $totalQuestions) {
      header('Location: result.php');
      exit;
    }

    // otherwise just reload quiz.php for the next question
    header('Location: quiz.php');
    exit;
  }
}

$currentIndex = (int) $_SESSION['question_index'];
$currentQuestion = $questions[$currentIndex];
$questionNumber = $currentIndex + 1;
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

        <?php if (!empty($errors['answer'])): ?>
          <div class="message-box error-box">
            <?php echo e($errors['answer']); ?>
          </div>
        <?php endif; ?>

        <form action="quiz.php" method="post" class="quiz-form">
          <div class="choice-list">
            <?php foreach ($currentQuestion['choices'] as $choice): ?>
              <label class="choice-card">
                <input
                  type="radio"
                  name="answer"
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
  </div>

</body>
</html>
