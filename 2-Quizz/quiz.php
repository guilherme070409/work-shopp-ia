<?php
header('Content-Type: application/json');

// Array de 20 perguntas sobre ABBA
$questions = [
    [
        'text' => 'Qual música do ABBA venceu o Eurovision em 1974?',
        'options' => ['Dancing Queen', 'Waterloo', 'Mamma Mia', 'Fernando'],
        'correct_answer' => 'Waterloo'
    ],
    [
        'text' => 'Qual é o nome do álbum lançado pelo ABBA em 1981?',
        'options' => ['Voulez-Vous', 'The Visitors', 'Super Trouper', 'Arrival'],
        'correct_answer' => 'The Visitors'
    ],
    [
        'text' => 'Quem são os dois vocalistas principais do ABBA?',
        'options' => ['Benny e Björn', 'Agnetha e Anni-Frid', 'Björn e Frida', 'Agnetha e Benny'],
        'correct_answer' => 'Agnetha e Anni-Frid'
    ],
    [
        'text' => 'Qual música do ABBA começa com "I don’t wanna talk"?',
        'options' => ['The Winner Takes It All', 'Knowing Me, Knowing You', 'SOS', 'Lay All Your Love on Me'],
        'correct_answer' => 'The Winner Takes It All'
    ],
    [
        'text' => 'Em que ano o ABBA lançou "Dancing Queen"?',
        'options' => ['1974', '1976', '1978', '1980'],
        'correct_answer' => '1976'
    ],
    [
        'text' => 'Qual música do ABBA fala sobre um soldado na guerra?',
        'options' => ['Fernando', 'Chiquitita', 'Take a Chance on Me', 'Money, Money, Money'],
        'correct_answer' => 'Fernando'
    ],
    [
        'text' => 'Qual é o primeiro álbum do ABBA?',
        'options' => ['Ring Ring', 'Waterloo', 'ABBA', 'Arrival'],
        'correct_answer' => 'Ring Ring'
    ],
    [
        'text' => 'Qual música do ABBA foi escrita para a filha de Benny e Anni-Frid?',
        'options' => ['Slipping Through My Fingers', 'The Day Before You Came', 'I Have a Dream', 'One of Us'],
        'correct_answer' => 'Slipping Through My Fingers'
    ],
    [
        'text' => 'Em que país o ABBA foi formado?',
        'options' => ['Suécia', 'Noruega', 'Dinamarca', 'Finlândia'],
        'correct_answer' => 'Suécia'
    ],
    [
        'text' => 'Qual música do ABBA tem uma batida disco marcante?',
        'options' => ['Voulez-Vous', 'Thank You for the Music', 'Knowing Me, Knowing You', 'Mamma Mia'],
        'correct_answer' => 'Voulez-Vous'
    ],
    [
        'text' => 'Qual música do ABBA fala sobre dinheiro?',
        'options' => ['Money, Money, Money', 'SOS', 'Fernando', 'Chiquitita'],
        'correct_answer' => 'Money, Money, Money'
    ],
    [
        'text' => 'Quem escreveu a maioria das músicas do ABBA?',
        'options' => ['Benny e Björn', 'Agnetha e Anni-Frid', 'Stig Anderson', 'Todos juntos'],
        'correct_answer' => 'Benny e Björn'
    ],
    [
        'text' => 'Qual música do ABBA foi regravada para o filme "Mamma Mia!"?',
        'options' => ['Waterloo', 'I Have a Dream', 'Lay All Your Love on Me', 'All of the Above'],
        'correct_answer' => 'All of the Above'
    ],
    [
        'text' => 'Em que ano o ABBA se separou oficialmente?',
        'options' => ['1980', '1982', '1983', '1985'],
        'correct_answer' => '1982'
    ],
    [
        'text' => 'Qual música do ABBA tem um videoclipe famoso com um trem?',
        'options' => ['The Day Before You Came', 'Happy New Year', 'When I Kissed the Teacher', 'Head Over Heels'],
        'correct_answer' => 'The Day Before You Came'
    ],
    [
        'text' => 'Qual é o nome completo de Anni-Frid, uma das vocalistas?',
        'options' => ['Anni-Frid Lyngstad', 'Anni-Frid Andersson', 'Anni-Frid Ulvaeus', 'Anni-Frid Faltskog'],
        'correct_answer' => 'Anni-Frid Lyngstad'
    ],
    [
        'text' => 'Qual música do ABBA foi lançada como single em 1977?',
        'options' => ['The Name of the Game', 'Take a Chance on Me', 'Eagle', 'Summer Night City'],
        'correct_answer' => 'The Name of the Game'
    ],
    [
        'text' => 'Qual álbum do ABBA inclui a música "Fernando"?',
        'options' => ['Arrival', 'ABBA: The Album', 'Greatest Hits', 'Voulez-Vous'],
        'correct_answer' => 'Greatest Hits'
    ],
    [
        'text' => 'Qual música do ABBA fala sobre um novo amor?',
        'options' => ['Take a Chance on Me', 'Knowing Me, Knowing You', 'SOS', 'Chiquitita'],
        'correct_answer' => 'Take a Chance on Me'
    ],
    [
        'text' => 'Qual é o último álbum de estúdio do ABBA?',
        'options' => ['The Visitors', 'Super Trouper', 'Voulez-Vous', 'Arrival'],
        'correct_answer' => 'The Visitors'
    ]
];

// Pegar o número de perguntas solicitado (padrão: 10)
$count = isset($_GET['count']) ? (int)$_GET['count'] : 10;
$count = max(1, min($count, count($questions))); // Limita entre 1 e o total de perguntas

// Embaralhar e selecionar perguntas
shuffle($questions);
$selected_questions = array_slice($questions, 0, $count);

// Retornar como JSON
echo json_encode([
    'success' => true,
    'data' => $selected_questions
]);
?>