<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التحقق من البريد الإلكتروني</title>
    <style>
        body { font-family: Arial, sans-serif; background: #111; color: #fff; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: #1d1d1d; padding: 30px; border-radius: 12px; width: 100%; max-width: 420px; box-shadow: 0 10px 30px rgba(0,0,0,.3); }
        h1 { margin-top: 0; font-size: 24px; }
        p { line-height: 1.7; }
        a { color: #d4af37; }
    </style>
</head>
<body>
<div class="card">
    <h1>تحقق من بريدك الإلكتروني</h1>
    <p>يرجى تأكيد عنوان بريدك الإلكتروني لاستخدام جميع ميزات الحساب.</p>
    <p><a href="{{ route('dashboard') }}">العودة إلى لوحة التحكم</a></p>
</div>
</body>
</html>
