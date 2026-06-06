// quiz.js
document.addEventListener('DOMContentLoaded', () => {
    let questions = [];
    let currentQuestionIndex = 0;
    let score = 0;
    let streak = 0;
    let bestScore = localStorage.getItem('cosmo_best_score') || 0;

    const questionText = document.getElementById('question-text');
    const optionsContainer = document.getElementById('options-container');
    const nextBtn = document.getElementById('next-btn');
    const scoreVal = document.getElementById('score-val');
    const streakVal = document.getElementById('streak-val');
    const bestVal = document.getElementById('best-val');
    const qTotal = document.getElementById('q-total');
    const qCurrent = document.getElementById('q-current');
    const categoryItems = document.querySelectorAll('.category-list li');

    bestVal.textContent = bestScore;

    // Fetch questions from the database
    async function fetchQuestions(category) {
        questionText.textContent = "Loading questions...";
        optionsContainer.innerHTML = '';
        nextBtn.classList.add('hidden');

        try {
            const response = await fetch(`quiz_api.php?category=${encodeURIComponent(category)}`);
            questions = await response.json();

            if (questions.error || questions.length === 0) {
                questionText.textContent = "Error loading questions. Did you run setup_db_quiz.php?";
                return;
            }

            // Reset state for new quiz
            currentQuestionIndex = 0;
            score = 0;
            streak = 0;
            scoreVal.textContent = score;
            streakVal.textContent = streak;
            qTotal.textContent = questions.length;

            loadQuestion();
        } catch (error) {
            questionText.textContent = "Failed to connect to the database.";
            console.error(error);
        }
    }

    function loadQuestion() {
        nextBtn.classList.add('hidden');
        qCurrent.textContent = currentQuestionIndex + 1;

        const q = questions[currentQuestionIndex];
        questionText.textContent = q.question;

        optionsContainer.innerHTML = '';
        const prefixes = ['a) ', 'b) ', 'c) ', 'd) '];

        q.options.forEach((option, index) => {
            const btn = document.createElement('button');
            btn.className = 'option-btn';
            btn.textContent = prefixes[index] + option;
            btn.onclick = () => handleAnswer(index, btn);
            optionsContainer.appendChild(btn);
        });
    }

    function handleAnswer(selectedIndex, btnElement) {
        const correctIndex = questions[currentQuestionIndex].correctIndex;
        const allButtons = document.querySelectorAll('.option-btn');

        // Disable all buttons to prevent multiple clicks
        allButtons.forEach(b => b.disabled = true);

        if (selectedIndex === correctIndex) {
            btnElement.classList.add('correct');
            score += 10;
            streak += 1;

            if (score > bestScore) {
                bestScore = score;
                localStorage.setItem('cosmo_best_score', bestScore);
                bestVal.textContent = bestScore;
            }
        } else {
            btnElement.classList.add('wrong');
            streak = 0;
            allButtons[correctIndex].classList.add('correct');
        }

        scoreVal.textContent = score;
        streakVal.textContent = streak;
        nextBtn.classList.remove('hidden');
    }

    nextBtn.addEventListener('click', () => {
        currentQuestionIndex++;
        if (currentQuestionIndex < questions.length) {
            loadQuestion();
        } else {
            questionText.textContent = "Quiz Complete!";
            optionsContainer.innerHTML = `<p style="font-size: 1.2rem; margin-top: 10px;">Your final score is <span style="color:var(--accent-blue)">${score}</span>. Select a category on the left to play again!</p>`;
            nextBtn.classList.add('hidden');
        }
    });

    // Handle sidebar category clicks
    categoryItems.forEach(item => {
        item.addEventListener('click', () => {
            // Update active styling
            categoryItems.forEach(i => i.classList.remove('active'));
            item.classList.add('active');

            // Fetch new questions
            fetchQuestions(item.textContent.trim());
        });
    });

    // Start with the initial active category
    const initialCategory = document.querySelector('.category-list li.active');
    fetchQuestions(initialCategory ? initialCategory.textContent.trim() : 'General Astronomy');
});