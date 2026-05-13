@extends('layouts.app')
@section('title', 'Attempt Quiz')
@section('page-title', 'Attempt: ' . $quiz->title)

@section('sidebar')
    <a href="{{ route('student.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('student.quizzes.index') }}" class="active"><i class="bi bi-journal-check me-2"></i>My Quizzes</a>
    <a href="{{ route('student.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>My Assignments</a>
    <a href="{{ route('student.results') }}"><i class="bi bi-graph-up me-2"></i>My Results</a>
@endsection

@section('content')

@php $totalQuestions = $quiz->questions->count(); @endphp

<style>
.quiz-wrapper { max-width: 750px; margin: 0 auto; }

.quiz-header {
    background: linear-gradient(135deg, #1e3a5f, #2d5491);
    border-radius: 16px;
    padding: 16px 20px;
    color: white;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}
.quiz-meta { font-size: 0.85rem; opacity: 0.85; flex-wrap: wrap; gap: 8px; }

.timer-box {
    background: rgba(255,255,255,0.15);
    border-radius: 12px;
    padding: 8px 14px;
    text-align: center;
    min-width: 90px;
    flex-shrink: 0;
    backdrop-filter: blur(4px);
}
.timer-box .timer-digits {
    font-size: 1.6rem;
    font-weight: 700;
    letter-spacing: 2px;
    line-height: 1;
}
.timer-box .timer-label { font-size: 0.65rem; opacity: 0.8; margin-top: 2px; }
.timer-box.warning .timer-digits { color: #ffc107; }
.timer-box.danger  .timer-digits { color: #ff6b6b; animation: pulse 1s infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.5} }

.progress-thin { height: 6px; border-radius: 3px; background: rgba(255,255,255,0.2); margin-top: 12px; }
.progress-thin .bar { height: 100%; border-radius: 3px; background: #4ade80; transition: width 1s linear, background 0.5s; }

.question-counter {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 14px;
    flex-wrap: wrap;
}
.q-dot {
    width: 30px; height: 30px;
    border-radius: 50%;
    border: 2px solid #dee2e6;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.72rem; font-weight: 600;
    cursor: default;
    background: white;
    color: #6c757d;
}
.q-dot.current  { border-color: #0d6efd; background: #0d6efd; color: white; }
.q-dot.answered { border-color: #198754; background: #198754; color: white; }
.q-dot.locked   { border-color: #adb5bd; background: #e9ecef; color: #adb5bd; }

.question-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    padding: 22px;
    margin-bottom: 18px;
    display: none;
}
@media (min-width: 576px) { .question-card { padding: 28px; } }
.question-card.active { display: block; animation: fadeIn 0.3s ease; }
@keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

.question-number {
    font-size: 0.78rem;
    font-weight: 600;
    color: #0d6efd;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
}
.question-text {
    font-size: 1.05rem;
    font-weight: 600;
    color: #1a1a2e;
    margin-bottom: 18px;
    line-height: 1.5;
}

.option-label {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    margin-bottom: 10px;
    border-radius: 10px;
    border: 2px solid #e9ecef;
    cursor: pointer;
    transition: all 0.2s;
    background: #fafafa;
}
.option-label:hover { border-color: #0d6efd; background: #f0f4ff; }
.option-label.is-selected { border-color: #0d6efd; background: #e8f0fe; font-weight: 600; }
.option-label.is-selected .option-circle { background: #0d6efd; border-color: #0d6efd; }
.option-label.is-selected .option-circle::after { display: block; }
.option-label.locked-option { pointer-events: none; opacity: 0.7; cursor: not-allowed; }

.option-circle {
    width: 20px; height: 20px;
    border-radius: 50%;
    border: 2px solid #adb5bd;
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s;
    position: relative;
}
.option-circle::after {
    content: '';
    width: 9px; height: 9px;
    border-radius: 50%;
    background: white;
    display: none;
}

.nav-buttons {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
    margin-top: 18px;
    flex-wrap: wrap;
}
.btn-nav {
    padding: 9px 22px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    display: flex; align-items: center; gap: 6px;
}
.btn-next { background: #0d6efd; color: white; }
.btn-next:hover { background: #0b5ed7; }
.btn-submit-final { background: #198754; color: white; }
.btn-submit-final:hover { background: #157347; }

.q-status { font-size: 0.82rem; color: #6c757d; font-weight: 500; margin-right: auto; }

.locked-notice {
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 0.8rem;
    color: #856404;
    margin-top: 12px;
    display: none;
}

/* Custom Modal */
.custom-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.55);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(3px);
}
.custom-overlay.show { display: flex; animation: overlayIn 0.2s ease; }
@keyframes overlayIn { from{opacity:0} to{opacity:1} }

.custom-modal {
    background: white;
    border-radius: 20px;
    padding: 30px 24px;
    max-width: 420px;
    width: 90%;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    animation: modalIn 0.25s ease;
}
@keyframes modalIn { from{transform:scale(0.85);opacity:0} to{transform:scale(1);opacity:1} }

.modal-icon {
    width: 56px; height: 56px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 14px;
    font-size: 1.6rem;
}
.modal-icon.green  { background: #d1fae5; }
.modal-icon.orange { background: #fff3cd; }

.modal-title { font-size: 1.15rem; font-weight: 700; color: #1a1a2e; margin-bottom: 8px; }
.modal-msg   { font-size: 0.88rem; color: #6c757d; margin-bottom: 22px; line-height: 1.5; }

.modal-btns  { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
.modal-btn {
    padding: 9px 24px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}
.modal-btn.cancel  { background: #f1f3f5; color: #495057; }
.modal-btn.cancel:hover { background: #dee2e6; }
.modal-btn.confirm { background: #198754; color: white; }
.modal-btn.confirm:hover { background: #157347; }
</style>

{{-- Custom Confirm Modal --}}
<div class="custom-overlay" id="submit-overlay">
    <div class="custom-modal">
        <div class="modal-icon" id="modal-icon">✅</div>
        <div class="modal-title" id="modal-title">Submit Quiz?</div>
        <div class="modal-msg" id="modal-msg">Are you sure you want to submit? This cannot be undone.</div>
        <div class="modal-btns">
            <button class="modal-btn cancel" onclick="closeModal()">Cancel</button>
            <button class="modal-btn confirm" onclick="confirmSubmit()">Yes, Submit</button>
        </div>
    </div>
</div>

<form action="{{ route('student.quizzes.submit', $quiz) }}" method="POST" id="quiz-form">
@csrf

<div class="quiz-wrapper">

    {{-- Header --}}
    <div class="quiz-header">
        <div class="d-flex justify-content-between align-items-start gap-3">
            <div class="flex-grow-1">
                <h5 class="mb-1 fw-bold">{{ $quiz->title }}</h5>
                <div class="quiz-meta d-flex">
                    <span><i class="bi bi-book me-1"></i>{{ $quiz->subject->name ?? '' }}</span>
                    <span class="ms-3"><i class="bi bi-star me-1"></i>{{ $quiz->total_marks }} Marks</span>
                    <span class="ms-3"><i class="bi bi-list-ol me-1"></i>{{ $totalQuestions }} Qs</span>
                </div>
            </div>
            <div class="timer-box" id="timer-box">
                <div class="timer-digits" id="timer-display">01:30</div>
                <div class="timer-label">Per Question</div>
            </div>
        </div>
        <div class="progress-thin">
            <div class="bar" id="timer-bar" style="width:100%"></div>
        </div>
    </div>

    {{-- Question Dots --}}
    <div class="question-counter" id="question-dots"></div>

    {{-- Questions --}}
    @foreach($quiz->questions as $index => $question)
    <div class="question-card {{ $index === 0 ? 'active' : '' }}" id="qcard-{{ $index }}">

        <div class="question-number">Question {{ $index + 1 }} of {{ $totalQuestions }}</div>
        <div class="question-text">{{ $question->question_text }}</div>
        <span class="badge bg-light text-secondary border mb-3">{{ $question->marks }} mark(s)</span>

        @if($question->question_type === 'mcq')
            @foreach($question->options as $option)
            <label class="option-label" onclick="selectOption(this, {{ $index }})">
                <div class="option-circle"></div>
                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}"
                       class="option-radio" style="display:none;">
                <span>{{ $option }}</span>
            </label>
            @endforeach

        @elseif($question->question_type === 'true_false')
            @foreach(['True', 'False'] as $option)
            <label class="option-label" onclick="selectOption(this, {{ $index }})">
                <div class="option-circle"></div>
                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}"
                       class="option-radio" style="display:none;">
                <span>{{ $option }}</span>
            </label>
            @endforeach
        @endif

        <div class="locked-notice" id="locked-notice-{{ $index }}">
            <i class="bi bi-lock-fill me-1"></i>This question is locked. You cannot change your answer.
        </div>

        <div class="nav-buttons">
            <span class="q-status" id="status-{{ $index }}">Not answered</span>

            @if($index === $totalQuestions - 1)
                <button type="button" class="btn-nav btn-submit-final" onclick="submitQuiz()">
                    <i class="bi bi-check-circle me-1"></i>Submit Quiz
                </button>
            @else
                <button type="button" class="btn-nav btn-next" id="next-btn-{{ $index }}" onclick="nextQuestion({{ $index }})">
                    Next <i class="bi bi-arrow-right"></i>
                </button>
            @endif
        </div>
    </div>
    @endforeach

</div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var totalQ      = {{ $totalQuestions }};
    var currentQ    = 0;
    var answered    = {};
    var locked      = {};
    var isSubmitted = false;
    var SECS_PER_Q  = 90;
    var remaining   = SECS_PER_Q;
    var timerInterval;

    var dotsContainer = document.getElementById('question-dots');
    for (var i = 0; i < totalQ; i++) {
        var dot = document.createElement('div');
        dot.className = 'q-dot' + (i === 0 ? ' current' : ' locked');
        dot.textContent = i + 1;
        dot.setAttribute('id', 'dot-' + i);
        dotsContainer.appendChild(dot);
    }

    function updateDots() {
        for (var i = 0; i < totalQ; i++) {
            var dot = document.getElementById('dot-' + i);
            dot.className = 'q-dot';
            if (i === currentQ)   dot.classList.add('current');
            else if (answered[i]) dot.classList.add('answered');
            else                  dot.classList.add('locked');
        }
    }

    function moveNext() {
        if (currentQ >= totalQ - 1) return;
        locked[currentQ] = true;
        lockCurrentQuestion();
        document.getElementById('qcard-' + currentQ).classList.remove('active');
        currentQ++;
        document.getElementById('qcard-' + currentQ).classList.add('active');
        updateDots();
        resetTimer();
    }

    window.nextQuestion = function(index) { moveNext(); };

    function lockCurrentQuestion() {
        var card = document.getElementById('qcard-' + currentQ);
        card.querySelectorAll('.option-label').forEach(function(l) { l.classList.add('locked-option'); });
        var notice = document.getElementById('locked-notice-' + currentQ);
        if (notice) notice.style.display = 'block';
        var nextBtn = document.getElementById('next-btn-' + currentQ);
        if (nextBtn) nextBtn.style.display = 'none';
    }

    window.selectOption = function(label, qIndex) {
        if (locked[qIndex]) return;
        var card = label.closest('.question-card');
        card.querySelectorAll('.option-label').forEach(function(l) { l.classList.remove('is-selected'); });
        label.classList.add('is-selected');
        label.querySelector('.option-radio').checked = true;
        answered[qIndex] = true;
        var statusEl = document.getElementById('status-' + qIndex);
        if (statusEl) statusEl.textContent = '✓ Answered';
        updateDots();
    };

    window.submitQuiz = function() {
        if (isSubmitted) return;
        var unanswered = totalQ - Object.keys(answered).length;
        var overlay = document.getElementById('submit-overlay');
        var icon    = document.getElementById('modal-icon');
        var title   = document.getElementById('modal-title');
        var msg     = document.getElementById('modal-msg');

        if (unanswered > 0) {
            icon.className  = 'modal-icon orange';
            icon.textContent = '⚠️';
            title.textContent = 'Unanswered Questions!';
            msg.textContent   = unanswered + ' question(s) left unanswered. Submit anyway? Unanswered questions will get 0 marks.';
        } else {
            icon.className  = 'modal-icon green';
            icon.textContent = '✅';
            title.textContent = 'Submit Quiz?';
            msg.textContent   = 'All questions answered! Submit now? This cannot be undone.';
        }
        overlay.classList.add('show');
    };

    window.closeModal    = function() { document.getElementById('submit-overlay').classList.remove('show'); };

    window.confirmSubmit = function() {
        if (isSubmitted) return;
        isSubmitted = true;
        clearInterval(timerInterval);
        document.getElementById('quiz-form').submit();
    };

    function pad(n) { return n < 10 ? '0' + n : '' + n; }

    function resetTimer() {
        clearInterval(timerInterval);
        remaining = SECS_PER_Q;
        startTimer();
    }

    function startTimer() {
        var display  = document.getElementById('timer-display');
        var bar      = document.getElementById('timer-bar');
        var timerBox = document.getElementById('timer-box');

        function tick() {
            if (isSubmitted) return;
            display.textContent = pad(Math.floor(remaining / 60)) + ':' + pad(remaining % 60);
            bar.style.width = ((remaining / SECS_PER_Q) * 100) + '%';
            timerBox.className = 'timer-box';
            if (remaining <= 10) { timerBox.classList.add('danger');  bar.style.background = '#ff6b6b'; }
            else if (remaining <= 30) { timerBox.classList.add('warning'); bar.style.background = '#ffc107'; }
            else { bar.style.background = '#4ade80'; }
            if (remaining <= 0) {
                clearInterval(timerInterval);
                if (currentQ < totalQ - 1) { moveNext(); }
                else if (!isSubmitted) {
                    isSubmitted = true;
                    document.getElementById('submit-overlay').classList.remove('show');
                    document.getElementById('quiz-form').submit();
                }
                return;
            }
            remaining--;
        }
        tick();
        timerInterval = setInterval(tick, 1000);
    }

    startTimer();
    updateDots();
});
</script>
@endsection
