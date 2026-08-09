<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استعادة كلمة المرور</title>
    <style>
        body { font-family: Arial, sans-serif; background: #111; color: #fff; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: #1d1d1d; padding: 30px; border-radius: 12px; width: 100%; max-width: 420px; box-shadow: 0 10px 30px rgba(0,0,0,.3); }
        h1 { margin-top: 0; font-size: 24px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 6px; }
        input { width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #444; background: #111; color: #fff; }
        button { width: 100%; padding: 12px; border: 0; border-radius: 8px; background: #d4af37; color: #111; font-weight: 700; cursor: pointer; }
        .errors { background: #3a1616; border: 1px solid #8a2f2f; padding: 10px; border-radius: 8px; margin-bottom: 15px; }
        .success { background: #163a1e; border: 1px solid #2c7a3d; padding: 10px; border-radius: 8px; margin-bottom: 15px; }
        .link { margin-top: 15px; text-align: center; }
        .link a { color: #d4af37; text-decoration: none; }
    </style>
</head>
<body>
<div class="card">
    <h1>استعادة كلمة المرور</h1>
    @if (session('status'))
        <div class="success">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-group">
            <label for="email">البريد الإلكتروني</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        </div>
        <button type="submit">إرسال رابط الاستعادة</button>
    </form>
    <div class="link">
        <a href="{{ route('login') }}">العودة لتسجيل الدخول</a>
    </div>
</div>
</body>
</html>
