<?php
require_once __DIR__ . '/bootstrap.php';

// keeping quiz data in one place so quiz.php stays cleaner
// later on, this can grow into more categories or difficulty levels

function getQuizQuestions(): array {
  return [
    [
      'question' => 'What does PHP stand for?',
      'choices' => [
        'Personal Home Page',
        'Private Hypertext Processor',
        'Preprocessed Hyperlink Page',
        'Programming Home Protocol'
      ],
      'answer' => 'Personal Home Page'
    ],
    [
      'question' => 'Which superglobal is commonly used to store session data in PHP?',
      'choices' => [
        '$_POST',
        '$_COOKIE',
        '$_SESSION',
        '$_SERVER'
      ],
      'answer' => '$_SESSION'
    ],
    [
      'question' => 'Which HTML tag is used to create a hyperlink?',
      'choices' => [
        '<p>',
        '<a>',
        '<link>',
        '<href>'
      ],
      'answer' => '<a>'
    ],
    [
      'question' => 'What CSS property is commonly used to change text color?',
      'choices' => [
        'font-color',
        'text-style',
        'color',
        'foreground'
      ],
      'answer' => 'color'
    ],
    [
      'question' => 'Which method is safer for sending passwords in a form?',
      'choices' => [
        'GET',
        'POST',
        'LINK',
        'OPEN'
      ],
      'answer' => 'POST'
    ]
  ];
}
