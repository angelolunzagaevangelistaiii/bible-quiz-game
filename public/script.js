// ---- Quiz Globals ----
let totalQuestions = 0;
let timePer = 0;
let currentIndex = 0;
let questions = [];
let score = 0;
let timerInterval = null;

// ---- Initialization ----
function initQuiz(n, t) {
    totalQuestions = n;
    timePer = t;

    fetch(`api.php?action=get_questions&n=${totalQuestions}`)
        .then(r => r.json())
        .then(data => {
            questions = data;
            if (!questions.length) {
                document.getElementById('quiz-area').innerHTML = '<p>No questions available.</p>';
                return;
            }
            currentIndex = 0;
            score = 0;
            showQuestion();
        });
}

// ---- Display Question ----
function showQuestion() {
    const q = questions[currentIndex];
    const qa = `
        <div class="question">
            <p><strong>Question ${currentIndex + 1} / ${totalQuestions}</strong></p>
            <p class="scripture">${escapeHtml(q.scripture_ref)}</p>
            <p class="qtext">${escapeHtml(q.question)}</p>
            <div class="options">
                <button class="opt" data-val="A">${escapeHtml(q.option_a)}</button>
                <button class="opt" data-val="B">${escapeHtml(q.option_b)}</button>
                <button class="opt" data-val="C">${escapeHtml(q.option_c)}</button>
                <button class="opt" data-val="D">${escapeHtml(q.option_d)}</button>
            </div>
            <p>Time left: <span id="time-left">${timePer}</span>s</p>
        </div>
    `;

    document.getElementById('quiz-area').innerHTML = qa;

    // Attach answer buttons
    document.querySelectorAll('.opt').forEach(btn => {
        btn.addEventListener('click', () =>
            answerQuestion(btn.getAttribute('data-val'))
        );
    });

    startTimer(timePer);
}

// ---- Timer ----
function startTimer(seconds) {
    let t = seconds;
    document.getElementById('time-left').innerText = t;

    clearInterval(timerInterval);
    timerInterval = setInterval(() => {
        t--;
        document.getElementById('time-left').innerText = t;
        if (t <= 0) {
            clearInterval(timerInterval);
            nextQuestion(false); // timed out
        }
    }, 1000);
}

// ---- Answer Logic ----
function answerQuestion(selected) {
    clearInterval(timerInterval);
    const q = questions[currentIndex];
    const correct = q.correct;

    if (selected === correct) score++;

    nextQuestion();
}

function nextQuestion() {
    currentIndex++;

    if (currentIndex < totalQuestions && currentIndex < questions.length) {
        showQuestion();
    } else {
        finishQuiz();
    }
}

// ---- Finish Quiz ----
function finishQuiz() {
    document.getElementById('quiz-area').style.display = 'none';
    const resultBox = document.getElementById('result');
    resultBox.style.display = 'block';

    const percent = Math.round((score / totalQuestions) * 100);
    resultBox.innerHTML = `
        <h3>Finished!</h3>
        <p>Your score: ${score} / ${totalQuestions} (${percent}%)</p>
        <p>Saving score...</p>
    `;

    const form = new FormData();
    form.append('action', 'submit_score');
    form.append('score', score);
    form.append('total', totalQuestions);

    fetch('api.php', { method: 'POST', body: form })
        .then(r => r.json())
        .then(() => {
            resultBox.innerHTML += `<p>Saved. <a href="leaderboard.php">View leaderboard</a></p>`;
        });
}

// ---- Utility ----
function escapeHtml(s) {
    if (!s) return '';
    return s.replace(/[&<>"']/g, m => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;'
    }[m]));
}
