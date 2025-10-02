<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz de Jogos de Tabuleiro 🎲</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>🎲 Quiz de Jogos de Tabuleiro ♟️</h1>
        <div id="loading" class="loading">
            <p>🔄 Carregando e traduzindo perguntas...</p>
            <div id="translation-progress"></div>
        </div>
        <div id="quiz" style="display: none;"></div>
        <div style="text-align: center;">
            <button id="next-btn" onclick="nextQuestion()" style="display: none;">Próxima Pergunta →</button>
            <button id="submit-btn" onclick="submitAnswer()" style="display: none;">Enviar Resposta</button>
        </div>
        <div id="result"></div>
    </div>

    <script>
        let questions = [];
        let currentQuestionIndex = 0;
        let score = 0;

        // DECODIFICA HTML ENTITIES
        function decodeHTML(html) {
            const txt = document.createElement('textarea');
            txt.innerHTML = html;
            return txt.value;
        }

        // API GOOGLE TRANSLATE (gratuita para uso limitado)
        async function traduzirComAPI(texto) {
            // Método 1: API Google Translate (funciona sem chave para pequenos textos)
            try {
                const response = await fetch(`https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=pt&dt=t&q=${encodeURIComponent(texto)}`);
                const data = await response.json();
                return data[0][0][0];
            } catch (error) {
                console.log('Erro Google Translate, usando fallback:', error);
                return traduzirFallback(texto);
            }
        }

        // FALLBACK para quando a API falhar
        function traduzirFallback(texto) {
            const dicionario = {
                'board game': 'jogo de tabuleiro',
                'card game': 'jogo de cartas',
                'monopoly': 'banco imobiliário',
                'chess': 'xadrez',
                'checkers': 'damas',
                'risk': 'war',
                'clue': 'detetive',
                'scrabble': 'scrabble',
                'catan': 'catan',
                'pandemic': 'pandemic',
                'released': 'lançado',
                'published': 'publicado',
                'created': 'criado',
                'players': 'jogadores',
                'player': 'jogador',
                'strategy': 'estratégia',
                'classic': 'clássico',
                'popular': 'popular',
                'won': 'ganhou',
                'award': 'prêmio',
                'year': 'ano',
                'which': 'qual',
                'what': 'o que',
                'how many': 'quantos',
                'when': 'quando'
            };

            let textoTraduzido = texto.toLowerCase();
            
            for (const [en, pt] of Object.entries(dicionario)) {
                const regex = new RegExp(`\\b${en}\\b`, 'gi');
                textoTraduzido = textoTraduzido.replace(regex, pt);
            }

            return textoTraduzido.charAt(0).toUpperCase() + textoTraduzido.slice(1);
        }

        // CARREGA E TRADUZ PERGUNTAS COM API REAL
        async function loadQuestions() {
            try {
                const response = await fetch('https://opentdb.com/api.php?amount=10&category=16&difficulty=easy');
                const data = await response.json();
                
                if (data.response_code === 0 && data.results.length > 0) {
                    const progressDiv = document.getElementById('translation-progress');
                    
                    // Traduz cada pergunta individualmente
                    questions = [];
                    for (let i = 0; i < data.results.length; i++) {
                        const q = data.results[i];
                        progressDiv.innerHTML = `Traduzindo pergunta ${i + 1} de ${data.results.length}...`;
                        
                        // Traduz pergunta
                        const perguntaTraduzida = await traduzirComAPI(decodeHTML(q.question));
                        
                        // Traduz resposta correta
                        const respostaCorretaTraduzida = await traduzirComAPI(decodeHTML(q.correct_answer));
                        
                        // Traduz opções incorretas
                        const opcoesIncorretasTraduzidas = await Promise.all(
                            q.incorrect_answers.map(async (opcao) => 
                                await traduzirComAPI(decodeHTML(opcao))
                            )
                        );
                        
                        // Combina e embaralha opções
                        const todasOpcoes = [...opcoesIncorretasTraduzidas, respostaCorretaTraduzida]
                            .sort(() => Math.random() - 0.5);

                        questions.push({
                            text: perguntaTraduzida,
                            options: todasOpcoes,
                            correct_answer: respostaCorretaTraduzida,
                            category: 'Jogos de Tabuleiro',
                            difficulty: q.difficulty === 'easy' ? 'Fácil' : 
                                       q.difficulty === 'medium' ? 'Médio' : 'Difícil',
                            original: decodeHTML(q.question) // Para debug
                        });
                    }
                    
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('quiz').style.display = 'block';
                    displayQuestion();
                    
                } else {
                    throw new Error('API não retornou perguntas');
                }
            } catch (error) {
                console.error('Erro:', error);
                document.getElementById('loading').innerHTML = `
                    <p class="incorrect">❌ Erro: ${error.message}</p>
                    <button onclick="carregarPerguntasLocais()">Usar Perguntas em Português</button>
                `;
            }
        }

        // ... (as funções displayQuestion, submitAnswer, nextQuestion, showResult permanecem as mesmas)
        function displayQuestion() {
            if (currentQuestionIndex >= questions.length) {
                showResult();
                return;
            }

            const question = questions[currentQuestionIndex];
            const quizDiv = document.getElementById('quiz');
            
            quizDiv.innerHTML = `
                <div class="question-meta">
                    ${question.category} • ${question.difficulty}
                </div>
                <h2>${currentQuestionIndex + 1}. ${question.text}</h2>
                <form id="quiz-form">
                    ${question.options.map((option, index) => `
                        <label>
                            <input type="radio" name="answer" value="${option}">
                            <span style="font-weight: bold; margin-right: 10px;">${String.fromCharCode(65 + index)}.</span>
                            ${option}
                        </label>
                    `).join('')}
                </form>
                ${question.original ? `
                <div style="margin-top: 10px; font-size: 0.8em; color: #666; text-align: center;">
                    <em>Original: "${question.original}"</em>
                </div>
                ` : ''}
            `;
            
            document.getElementById('submit-btn').style.display = 'inline-block';
            document.getElementById('next-btn').style.display = 'none';
            document.getElementById('result').innerHTML = '';
        }

        function submitAnswer() {
            const form = document.getElementById('quiz-form');
            const selected = form.querySelector('input[name="answer"]:checked');
            
            if (!selected) {
                document.getElementById('result').innerHTML = '<p style="color: #667eea;">⚠️ Selecione uma resposta!</p>';
                return;
            }

            const question = questions[currentQuestionIndex];
            const labels = form.querySelectorAll('label');
            const selectedLabel = selected.parentElement;
            
            // Remove estilos anteriores
            labels.forEach(label => label.classList.remove('correct-answer', 'incorrect-answer'));
            
            // Destaca resposta correta
            labels.forEach(label => {
                const labelText = label.textContent.replace(/^[A-Z]\.\s/, '').trim();
                if (labelText === question.correct_answer) {
                    label.classList.add('correct-answer');
                }
            });

            // Verifica se acertou
            if (selected.value === question.correct_answer) {
                score++;
                document.getElementById('result').innerHTML = '<p class="correct">🎉 Acertou! +1 ponto!</p>';
            } else {
                selectedLabel.classList.add('incorrect-answer');
                document.getElementById('result').innerHTML = `<p class="incorrect">😕 Errou! Resposta: "${question.correct_answer}"</p>`;
            }

            // Desabilita novas seleções
            const allInputs = form.querySelectorAll('input');
            allInputs.forEach(input => input.disabled = true);

            document.getElementById('submit-btn').style.display = 'none';
            document.getElementById('next-btn').style.display = 'inline-block';
        }

        function nextQuestion() {
            currentQuestionIndex++;
            displayQuestion();
        }

        function showResult() {
            const percentage = (score / questions.length) * 100;
            let message, emoji;
            
            if (percentage >= 80) { message = "🏆 Mestre dos Jogos!"; emoji = "♟️"; }
            else if (percentage >= 60) { message = "⭐ Ótimo conhecimento!"; emoji = "🎯"; }
            else { message = "🎲 Continue praticando!"; emoji = "👏"; }
            
            document.getElementById('quiz').innerHTML = `
                <div style="text-align: center; padding: 30px;">
                    <h2>${emoji} Quiz Finalizado! ${emoji}</h2>
                    <p style="font-size: 1.3em; margin: 20px 0;">${message}</p>
                </div>
            `;
            
            document.getElementById('result').innerHTML = `
                <div style="text-align: center; background: #f7fafc; padding: 25px; border-radius: 15px; border: 2px solid #667eea;">
                    <h3 style="color: #764ba2; margin-bottom: 15px;">Pontuação Final</h3>
                    <div style="font-size: 3em; font-weight: bold; color: #764ba2; margin: 10px 0;">
                        ${score}<small style="font-size: 0.5em; color: #718096;">/${questions.length}</small>
                    </div>
                    <div style="font-size: 1.2em; color: #718096;">
                        ${percentage.toFixed(1)}% de acertos
                    </div>
                    <button onclick="location.reload()" style="margin-top: 20px;">🔄 Jogar Novamente</button>
                </div>
            `;
            
            document.getElementById('next-btn').style.display = 'none';
            document.getElementById('submit-btn').style.display = 'none';
        }

        function carregarPerguntasLocais() {
            questions = [
                {
                    text: "Qual jogo de tabuleiro envolve comprar e vender propriedades?",
                    options: ["War", "Banco Imobiliário", "Detetive", "Xadrez"],
                    correct_answer: "Banco Imobiliário",
                    category: "Jogos de Tabuleiro",
                    difficulty: "Fácil"
                },
                {
                    text: "Quantas casas tem um tabuleiro de xadrez?",
                    options: ["64", "32", "100", "81"],
                    correct_answer: "64",
                    category: "Jogos de Tabuleiro", 
                    difficulty: "Fácil"
                }
            ];
            document.getElementById('loading').style.display = 'none';
            document.getElementById('quiz').style.display = 'block';
            displayQuestion();
        }

        // Inicia o quiz
        loadQuestions();
    </script>
</body>
</html>