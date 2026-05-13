<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QAMS - Create Admin Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #2d5491 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            padding: 20px;
        }
        .setup-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px 36px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.35);
        }
        .brand-icon {
            width: 70px; height: 70px;
            background: linear-gradient(135deg, #1e3a5f, #2d5491);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            font-size: 2rem;
            box-shadow: 0 8px 20px rgba(30,58,95,0.3);
        }
        .brand h1 { color: #1e3a5f; font-size: 1.7rem; font-weight: 700; margin-bottom: 4px; }
        .form-control { border-radius: 10px; border: 1.5px solid #e2e8f0; }
        .form-control:focus { border-color: #2d5491; box-shadow: 0 0 0 3px rgba(45,84,145,0.12); }
        .btn-primary { border-radius: 10px; font-weight: 600; padding: 12px; }
        @media (max-width: 480px) {
            .setup-card { padding: 28px 20px; border-radius: 16px; }
            .brand h1 { font-size: 1.4rem; }
        }
    </style>
</head>
<body>
    <div class="setup-card">
        <div class="brand text-center mb-4">
            <div class="brand-icon">📚</div>
            <h1>QAMS</h1>
            <p class="text-muted">Create Your Admin Account</p>
        </div>

        <div class="alert alert-info py-2 mb-3">
            <small><strong>First-time setup:</strong> This page is only available once. After creating the admin account, all future users are registered from the admin panel.</small>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('setup.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Full Name</label>
                <input type="text" name="name" class="form-control form-control-lg"
                       value="{{ old('name') }}" required placeholder="e.g. Dr. Ahmad Khan">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" name="username" class="form-control form-control-lg"
                       value="{{ old('username') }}" required placeholder="e.g. admin">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control form-control-lg"
                       required placeholder="Min. 6 characters">
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control form-control-lg"
                       required placeholder="Repeat password">
            </div>
            <button type="submit" class="btn btn-primary btn-lg w-100">
                <i class="bi bi-shield-check me-2"></i>Create Admin Account
            </button>
        </form>
    </div>
</body>
</html>
