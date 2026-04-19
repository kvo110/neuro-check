<?php
require_once __DIR__ . '/bootstrap.php';

// keeping quiz questions in one place so the main quiz page
// doesn't turn into a giant mess later
function getQuizQuestions(): array {
  return [
    [
      'question' => 'What does PHP originally stand for?',
      'choices' => [
        'Personal Home Page',
        'Private Hypertext Processor',
        'Programming Hyperlink Protocol',
        'Public Hosting Platform'
      ],
      'answer' => 'Personal Home Page'
    ],
    [
      'question' => 'Which PHP superglobal is used to store session data?',
      'choices' => [
        '$_POST',
        '$_GET',
        '$_SESSION',
        '$_SERVER'
      ],
      'answer' => '$_SESSION'
    ],
    [
      'question' => 'Which HTML tag is used for a hyperlink?',
      'choices' => [
        '<a>',
        '<link>',
        '<p>',
        '<href>'
      ],
      'answer' => '<a>'
    ],
    [
      'question' => 'Which CSS property changes the text color?',
      'choices' => [
        'font-color',
        'text-color',
        'color',
        'foreground'
      ],
      'answer' => 'color'
    ],
    [
      'question' => 'Which form method is better for sending passwords?',
      'choices' => [
        'GET',
        'POST',
        'LINK',
        'LOAD'
      ],
      'answer' => 'POST'
    ]
  ];
}
