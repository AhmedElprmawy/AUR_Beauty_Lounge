<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الشخصي</title>
    <style>
        body { font-family: Arial, sans-serif; background: #111; color: #fff; padding: 40px; margin: 0; }
        .card { background: #1d1d1d; padding: 24px; border-radius: 12px; max-width: 520px; margin: 0 auto; box-shadow: 0 10px 30px rgba(0,0,0,.3); }
        h1 { margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 6px; }
        input { width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #444; background: #111; color: #fff; }
        button { padding: 10px 14px; border: 0; border-radius: 8px; background: #d4af37; color: #111; font-weight: 700; cursor: pointer; }
        .section { margin-top: 25px; }
        .errors { background: #3a1616; border: 1px solid #8a2f2f; padding: 10px; border-radius: 8px; margin-bottom: 15px; }
        .success { background: #163a1e; border: 1px solid #2c7a3d; padding: 10px; border-radius: 8px; margin-bottom: 15px; }
        .delete-btn { background: #c0392b; color: #fff; }
    </style>
</head>
<body>
<div class="card">
    <h1>الملف الشخصي</h1>
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

    <form method="POST" action="{{ url('/profile') }}">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <label for="name">الاسم</label>
            <input id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="form-group">
            <label for="email">البريد الإلكتروني</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>
        <button type="submit">حفظ التغييرات</button>
    </form>

    <div class="section">
        <h2>تغيير كلمة المرور</h2>
        <form method="POST" action="{{ url('/password') }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="current_password">كلمة المرور الحالية</label>
                <input id="current_password" type="password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="password">كلمة المرور الجديدة</label>
                <input id="password" type="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">تأكيد كلمة المرور</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>
            <button type="submit">تغيير كلمة المرور</button>
        </form>
    </div>

    <div class="section">
        <h2>حذف الحساب</h2>
        <form method="POST" action="{{ url('/profile') }}">
            @csrf
            @method('DELETE')
            <div class="form-group">
                <label for="delete_password">كلمة المرور الحالية</label>
                <input id="delete_password" type="password" name="password" required>
            </div>
            <button type="submit" class="delete-btn">حذف الحساب</button>
        </form>
    </div>
</div>
</body>
</html>
