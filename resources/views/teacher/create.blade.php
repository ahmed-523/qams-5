@extends('layouts.app')
@section('title', 'Create Quiz')
@section('page-title', 'Create New Quiz')

@section('sidebar')
<a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
<a href="{{ route('teacher.questions.index') }}"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
<a href="{{ route('teacher.quizzes.index') }}" class="active"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
<a href="{{ route('teacher.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
<a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection

@section('content')
<div class="w-100 d-flex justify-content-center py-4 py-md-5">
    <div class="card shadow-sm border-0 w-100" style="max-width:700px;">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <h5 class="mb-0 text-primary"><i class="bi bi-plus-circle me-2"></i>Create New Quiz</h5>
        </div>
        <div class="card-body p-4">

            @if($errors->any())
            <div class="alert alert-danger shadow-sm">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('teacher.quizzes.store') }}" method="POST" id="quiz-form">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary">Quiz Title *</label>
                    <input type="text" name="title" class="form-control shadow-sm" required value="{{ old('title') }}"
                        placeholder="e.g. Quiz 1">
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary">Subject *</label>
                        <select name="subject_id" class="form-select shadow-sm" required id="subject-select">
                            <option value="">-- Select Subject --</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" data-class="{{ $subject->class_id }}"
                                {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} ({{ $subject->class->name ?? '' }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary">Class *</label>
                        <select name="class_id" class="form-select shadow-sm" required id="class-select">
                            <option value="">-- Select Class --</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary">Number of Questions *</label>
                    <div class="input-group shadow-sm">
                        <input type="number" name="number_of_questions" id="number-of-questions"
                            class="form-control @error('number_of_questions') is-invalid @enderror" min="1"
                            placeholder="e.g. 5" value="{{ old('number_of_questions') }}" required>
                        <button type="button" class="btn btn-outline-secondary" id="check-btn">
                            <i class="bi bi-search me-1"></i> Check Bank
                        </button>
                    </div>
                    @error('number_of_questions')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <small class="text-muted mt-2 d-block"><i class="bi bi-info-circle me-1"></i>Questions will be
                        picked
                        randomly from your question bank.</small>
                    <div id="bank-check-result" class="mt-2 shadow-sm rounded" style="display:none;"></div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary">Deadline *</label>
                    <input type="datetime-local" name="deadline" class="form-control shadow-sm" required
                        value="{{ old('deadline') }}">
                </div>

                <hr class="text-muted my-4">

                <div class="d-flex justify-content-end gap-2 mt-2">
                    <a href="{{ route('teacher.quizzes.index') }}" class="btn btn-light border px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" id="submit-btn" disabled>
                        Create Quiz
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var subjectSelect = document.getElementById('subject-select');
    var numInput = document.getElementById('number-of-questions');
    var checkBtn = document.getElementById('check-btn');
    var resultBox = document.getElementById('bank-check-result');
    var submitBtn = document.getElementById('submit-btn');
    var CSRF_TOKEN = '{{ csrf_token() }}';
    var CHECK_URL = '{{ route("teacher.quizzes.checkQuestions") }}';
    var ADD_Q_URL = '{{ route("teacher.questions.create") }}';

    subjectSelect.addEventListener('change', function() {
        var classId = this.options[this.selectedIndex].getAttribute('data-class');
        if (classId) document.getElementById('class-select').value = classId;
        resetCheck();
    });

    numInput.addEventListener('input', resetCheck);

    checkBtn.addEventListener('click', function() {
        var subjectId = subjectSelect.value;
        var numQ = parseInt(numInput.value, 10);

        if (!subjectId) {
            showResult('warning', '&#9888; Please select a subject first.');
            return;
        }
        if (!numQ || numQ < 1) {
            showResult('warning', '&#9888; Please enter number of questions.');
            return;
        }

        checkBtn.disabled = true;
        checkBtn.textContent = 'Checking...';

        fetch(CHECK_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    subject_id: subjectId,
                    number_of_questions: numQ
                }),
            })
            .then(function(r) {
                if (!r.ok) throw new Error('Status ' + r.status);
                return r.json();
            })
            .then(function(data) {
                if (data.enough) {
                    showResult('success',
                        '&#10003; Bank has <strong>' + data.available +
                        '</strong> questions. ' +
                        '<strong>' + data.requested + '</strong> will be randomly selected.');
                    submitBtn.disabled = false;
                } else {
                    showResult('danger',
                        '&#10007; Need <strong>' + data.requested +
                        '</strong> but only <strong>' +
                        data.available + '</strong> exist. <a href="' + ADD_Q_URL +
                        '" class="alert-link">Add more &rarr;</a>');
                    submitBtn.disabled = true;
                }
            })
            .catch(function(err) {
                showResult('danger', '&#10007; ' + err.message);
                console.error(err);
            })
            .finally(function() {
                checkBtn.disabled = false;
                checkBtn.innerHTML = '<i class="bi bi-search me-1"></i> Check Bank';
            });
    });

    function showResult(type, html) {
        resultBox.className = 'alert alert-' + type + ' py-2 mb-0';
        resultBox.innerHTML = html;
        resultBox.style.display = 'block';
    }

    function resetCheck() {
        resultBox.style.display = 'none';
        submitBtn.disabled = true;
    }
});
</script>
@endsection