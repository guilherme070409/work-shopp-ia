<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz do ABBA</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Quiz do ABBA 🎶</h1>
        <div id="quiz"></div>
        <button id="next-btn" onclick="nextQuestion()" style="display: none;">Próxima Pergunta</button>
        <button id="submit-btn" onclick="submitAnswer()" style="display: none;">Enviar Resposta</button>
        <div id="result"></div>
    </div>

    <script>
        let questions = [];
        let currentQuestionIndex = 0;
        let score = 0;

        // Função para carregar perguntas da API
        async function loadQuestions() {
            try {
                const response = await fetch('quiz.php?count=10');
                const data = await response.json();
                if (data.success) {
                    questions = data.data;
                    displayQuestion();
                } else {
                    document.getElementById('quiz').innerHTML = '<p>Ops, algo deu errado! 😢</p>';
                }
            } catch (error) {
                console.error('Erro ao carregar perguntas:', error);
                document.getElementById('quiz').innerHTML = '<p>SOS! Não consegui carregar as perguntas!</p>';
            }
        }

        // Função para exibir a pergunta atual
        function displayQuestion() {
            if (currentQuestionIndex >= questions.length) {
                showResult();
                return;
            }

            const question = questions[currentQuestionIndex];
            const quizDiv = document.getElementById('quiz');
            quizDiv.innerHTML = `
                <h2>${currentQuestionIndex + 1}. ${question.text}</h2>
                <form id="quiz-form">
                    ${question.options.map(option => `
                        <label>
                            <input type="radio" name="answer" value="${option}" required>
                            ${option}
                        </label><br>
                    `).join('')}
                </form>
            `;
            document.getElementById('submit-btn').style.display = 'inline';
            document.getElementById('next-btn').style.display = 'none';
            document.getElementById('result').innerHTML = '';
        }

        // Função para enviar a resposta
        function submitAnswer() {
            const form = document.getElementById('quiz-form');
            const selectedAnswer = form.querySelector('input[name="answer"]:checked');
            if (!selectedAnswer) {
                alert('Por favor, selecione uma resposta!');
                return;
            }

            const question = questions[currentQuestionIndex];
            const resultDiv = document.getElementById('result');
            if (selectedAnswer.value === question.correct_answer) {
                resultDiv.innerHTML = '<p class="correct">Acertou! 🎉 Como "The Winner Takes It All"!</p>';
                score++;
            } else {
                resultDiv.innerHTML = `<p class="incorrect">Ops, não foi dessa vez! 😕 A certa é: ${question.correct_answer}</p>`;
            }

            document.getElementById('submit-btn').style.display = 'none';
            document.getElementById('next-btn').style.display = 'inline';
        }

        // Função para ir para a próxima pergunta
        function nextQuestion() {
            currentQuestionIndex++;
            if (currentQuestionIndex < questions.length) {
                displayQuestion();
            } else {
                showResult();
            }
        }

        // Função para exibir o resultado final
        function showResult() {
            const quizDiv = document.getElementById('quiz');
            const resultDiv = document.getElementById('result');
            quizDiv.innerHTML = '<p>Quiz finalizado! Veja seu resultado.</p>';
            resultDiv.innerHTML = `<p>Você acertou ${score} de ${questions.length}!</p>`;
            document.getElementById('next-btn').style.display = 'none';
        }

        // Carregar perguntas ao iniciar
        loadQuestions();
    </script>
</body>
</html>