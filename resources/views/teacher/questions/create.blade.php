@extends('layouts.app')
@section('title', 'Add Question')
@section('page-title', 'Add Questions to Question Bank')

@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.questions.index') }}" class="active"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection

@section('content')
<div class="form-card-wrap">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom pt-4 pb-3">
            <h5 class="mb-0 text-primary"><i class="bi bi-plus-circle me-2"></i>Create New Question</h5>
        </div>
        <div class="card-body p-4">

            @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('teacher.questions.store') }}" method="POST" id="qform">
                @csrf
                <div class="row g-4 mb-4">

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold text-secondary">Subject *</label>
                        <select name="subject_id" class="form-select" required>
                            <option value="">-- Select Subject --</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}"
                                {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold text-secondary">Question Type *</label>
                        <select name="question_type" id="q-type" class="form-select" required>
                            <option value="">-- Select Type --</option>
                            <option value="mcq" {{ old('question_type') == 'mcq' ? 'selected' : '' }}>MCQ (Multiple Choice)</option>
                            <option value="true_false" {{ old('question_type') == 'true_false' ? 'selected' : '' }}>True / False</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold text-secondary">Question Text *</label>
                        <textarea name="question_text" class="form-control" rows="3"
                            placeholder="Type your question here..." required>{{ old('question_text') }}</textarea>
                    </div>

                    <div class="col-12" id="mcq-options" style="display:none">
                        <label class="form-label fw-bold text-secondary">MCQ Options *</label>
                        <div class="row g-2">
                            @for($i = 0; $i < 4; $i++)
                            <div class="col-12 col-md-6">
                                <input type="text" name="options[]" id="option-{{ $i }}"
                                    class="form-control mcq-option-input" placeholder="Option {{ $i + 1 }}"
                                    value="{{ old('options.' . $i) }}">
                            </div>
                            @endfor
                        </div>
                        <small class="text-muted mt-2 d-block"><i class="bi bi-info-circle me-1"></i>Fill all 4 options. The correct answer dropdown will update automatically.</small>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold text-secondary">Correct Answer *</label>

                        <select name="correct_answer" id="correct-answer-mcq" class="form-select" style="display:none">
                            <option value="">-- Select correct option --</option>
                            @for($i = 0; $i < 4; $i++)
                                @if(old('options.' . $i))
                                <option value="{{ old('options.' . $i) }}"
                                    {{ old('correct_answer') == old('options.' . $i) ? 'selected' : '' }}>
                                    {{ old('options.' . $i) }}
                                </option>
                                @endif
                            @endfor
                        </select>

                        <select name="correct_answer" id="correct-answer-tf" class="form-select" style="display:none">
                            <option value="">-- Select --</option>
                            <option value="True" {{ old('correct_answer') == 'True'  ? 'selected' : '' }}>True</option>
                            <option value="False" {{ old('correct_answer') == 'False' ? 'selected' : '' }}>False</option>
                        </select>
                        <small class="text-muted mt-2 d-block" id="answer-hint"></small>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold text-secondary">Marks *</label>
                        <input type="number" name="marks" class="form-control" min="1"
                            value="{{ old('marks', 1) }}" required>
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end flex-wrap gap-2">
                    <a href="{{ route('teacher.questions.index') }}" class="btn btn-light border px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i>Save Question
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const qType = document.getElementById('q-type');
    const mcqOptions = document.getElementById('mcq-options');
    const answerMcq = document.getElementById('correct-answer-mcq');
    const answerTf = document.getElementById('correct-answer-tf');
    const answerHint = document.getElementById('answer-hint');
    const optionInputs = document.querySelectorAll('.mcq-option-input');

    function toggleOptions() {
        const type = qType.value;
        [answerMcq, answerTf].forEach(el => {
            el.style.display = 'none';
            el.disabled = true;
            el.removeAttribute('required');
        });
        optionInputs.forEach(el => {
            el.disabled = true;
            el.removeAttribute('required');
        });
        mcqOptions.style.display = 'none';
        answerHint.textContent = '';

        if (type === 'mcq') {
            mcqOptions.style.display = 'block';
            answerMcq.style.display = 'block';
            answerMcq.disabled = false;
            answerMcq.setAttribute('required', 'required');
            optionInputs.forEach(el => {
                el.disabled = false;
                el.setAttribute('required', 'required');
            });
            answerHint.textContent = 'Select one of the 4 options as correct.';
            syncMcqDropdown();
        } else if (type === 'true_false') {
            answerTf.style.display = 'block';
            answerTf.disabled = false;
            answerTf.setAttribute('required', 'required');
            answerHint.textContent = 'Choose True or False.';
        }
    }

    function syncMcqDropdown() {
        const previous = answerMcq.value;
        answerMcq.innerHTML = '<option value="">-- Select correct option --</option>';
        optionInputs.forEach((input, idx) => {
            const val = input.value.trim();
            if (val) {
                const opt = document.createElement('option');
                opt.value = val;
                opt.textContent = 'Option ' + (idx + 1) + ': ' + val;
                if (val === previous) opt.selected = true;
                answerMcq.appendChild(opt);
            }
        });
    }

    qType.addEventListener('change', toggleOptions);
    optionInputs.forEach(input => input.addEventListener('input', syncMcqDropdown));
    toggleOptions();
});
</script>
@endsection
