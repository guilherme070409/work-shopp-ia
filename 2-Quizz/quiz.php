<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$questions = [
    [
        'id' => 1,
        'text' => 'Qual música do ABBA venceu o Eurovision em 1974?',
        'options' => ['Dancing Queen', 'Waterloo', 'Mamma Mia', 'Fernando'],
        'correct_answer' => 'Waterloo'
    ],
    [
        'id' => 2, 
        'text' => 'Qual é o nome do álbum lançado pelo ABBA em 1981?',
        'options' => ['Voulez-Vous', 'The Visitors', 'Super Trouper', 'Arrival'],
        'correct_answer' => 'The Visitors'
    ],
    // ... coloque aqui as outras 18 perguntas do seu array original
];

$count = isset($_GET['count']) ? (int)$_GET['count'] : 5;
$count = max(1, min($count, count($questions)));

shuffle($questions);
$selected_questions = array_slice($questions, 0, $count);

echo json_encode([
    'success' => true,
    'data' => $selected_questions
]);
?>