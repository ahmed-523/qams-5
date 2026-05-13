<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QAMS - Login</title>
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
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px 36px;
            width: 100%;
            max-width: 420px;
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
        .brand h1 { color: #1e3a5f; font-size: 1.8rem; font-weight: 700; margin-bottom: 4px; }
        .brand p  { color: #64748b; font-size: 0.88rem; }
        .form-control, .form-control-lg {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            border-color: #2d5491;
            box-shadow: 0 0 0 3px rgba(45,84,145,0.12);
        }
        .btn-primary {
            border-radius: 10px;
            font-weight: 600;
            letter-spacing: 0.3px;
            padding: 12px;
        }
        @media (max-width: 480px) {
            .login-card { padding: 28px 20px; border-radius: 16px; }
            .brand h1 { font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand text-center mb-4">
            <div class="brand-icon">📚</div>
            <h1>QAMS</h1>
            <p>Quiz &amp; Assignment Management System</p>
        </div>

        @if(session('info'))
            <div class="alert alert-info py-2 mb-3">{{ session('info') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger py-2 mb-3">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" name="username" class="form-control form-control-lg"
                       value="{{ old('username') }}" required autofocus
                       placeholder="Enter your username">
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control form-control-lg"
                       required placeholder="Enter your password">
            </div>
            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </form>

        @if(!\App\Models\User::where('role', 'admin')->exists())
        <div class="text-center">
            <a href="{{ route('setup') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-person-plus me-1"></i>Create Admin Account
            </a>
        </div>
        @endif
    </div>
</body>
</html>
