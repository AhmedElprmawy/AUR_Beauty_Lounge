<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AUR Beauty Lounge - تسجيل الدخول</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;800&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary-gold: #D4AF37;
            --dark-gold: #B5952F;
            --bg-dark: #121212;
            --surface-dark: #1E1E1E;
            --surface-light: #2A2A2A;
            --text-main: #FFFFFF;
            --text-muted: #A0A0A0;
            --border-color: #333333;
            --danger-color: #ff4d4d;
            --success-color: #4caf50;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--bg-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
        }

        .login-box {
            background: var(--surface-dark);
            border-radius: 16px;
            padding: 40px 35px;
            border: 1px solid var(--border-color);
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .login-header .logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 2px solid var(--primary-gold);
            object-fit: cover;
            margin-bottom: 15px;
        }

        .login-header h1 {
            color: var(--primary-gold);
            font-size: 24px;
            font-weight: 800;
        }

        .login-header p {
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 5px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 18px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background: var(--bg-dark);
            color: var(--text-main);
            font-size: 16px;
            font-family: 'Cairo', sans-serif;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-group input:focus {
            border-color: var(--primary-gold);
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.1);
        }

        .form-group input::placeholder {
            color: var(--text-muted);
            font-size: 14px;
        }

        .form-group .input-error {
            color: var(--danger-color);
            font-size: 13px;
            margin-top: 5px;
            display: none;
        }

        .form-group .input-error.show {
            display: block;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .form-options .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            font-size: 14px;
        }

        .form-options .remember input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary-gold);
        }

        .form-options .forgot {
            color: var(--primary-gold);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .form-options .forgot:hover {
            text-decoration: underline;
        }

        /* ===== زر تسجيل الدخول ===== */
        .login-btn {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 10px;
            background: var(--primary-gold);
            color: #000;
            font-size: 18px;
            font-weight: 800;
            font-family: 'Cairo', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            min-height: 56px;
        }

        .login-btn:hover {
            background: var(--dark-gold);
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(212, 175, 55, 0.3);
        }

        .login-btn:active {
            transform: scale(0.98);
        }

        .login-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }

        /* النص */
        .login-btn .btn-text {
            transition: all 0.3s ease;
        }

        /* Spinner */
        .login-btn .spinner {
            display: none;
            width: 24px;
            height: 24px;
            border: 3px solid rgba(0, 0, 0, 0.2);
            border-top: 3px solid #000;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        .login-btn.loading .spinner {
            display: block;
        }

        .login-btn.loading .btn-text {
            opacity: 0.6;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* رسالة الخطأ العامة */
        .login-error {
            background: rgba(255, 77, 77, 0.15);
            color: var(--danger-color);
            padding: 12px 16px;
            border-radius: 8px;
            border-right: 4px solid var(--danger-color);
            margin-bottom: 20px;
            display: none;
            font-size: 14px;
        }

        .login-error.show {
            display: block;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            color: var(--text-muted);
            font-size: 14px;
        }

        .register-link a {
            color: var(--primary-gold);
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: var(--primary-gold);
            color: #000;
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            z-index: 9999;
            transition: transform 0.4s ease;
            font-family: 'Cairo', sans-serif;
        }

        .toast.show {
            transform: translateX(-50%) translateY(0);
        }

        .toast.error {
            background: var(--danger-color);
            color: #fff;
        }

        .toast.success {
            background: var(--success-color);
            color: #fff;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-box {
                padding: 30px 20px;
            }

            .login-header h1 {
                font-size: 20px;
            }

            .form-options {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-box">

            <!-- Header -->
            <div class="login-header">
                <img src="{{ asset('images/logo.jpeg') }}" alt="AUR" class="logo">
                <h1>AUR Beauty Lounge</h1>
                <p>تسجيل الدخول إلى لوحة التحكم</p>
            </div>

            <!-- رسالة الخطأ العامة -->
            <div class="login-error" id="loginError">
                ⚠️ <span id="errorMessage">حدث خطأ في تسجيل الدخول</span>
            </div>

            <!-- نموذج تسجيل الدخول -->
            <form id="loginForm" method="POST" action="{{ route('login.submit') }}">
                @csrf

                <!-- اسم المستخدم -->
                <div class="form-group">
                    <label for="username">اسم المستخدم</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="أدخل اسم المستخدم"
                        value="{{ old('username') }}"
                        required
                        autofocus
                    >
                    <div class="input-error" id="usernameError">يرجى إدخال اسم مستخدم صالح</div>
                </div>

                <!-- كلمة المرور -->
                <div class="form-group">
                    <label for="password">🔒 كلمة المرور</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        required
                    >
                    <div class="input-error" id="passwordError">كلمة المرور غير صحيحة</div>
                </div>

                <!-- خيارات -->
                <div class="form-options">
                    <label class="remember">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        تذكرني
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot">نسيت كلمة المرور؟</a>
                    @endif
                </div>

                <!-- زر تسجيل الدخول -->
                <button type="submit" class="login-btn" id="loginBtn">
                    <span class="btn-text">🚀 تسجيل الدخول</span>
                    <div class="spinner"></div>
                </button>

            </form>

            <div class="register-link">
                <a href="{{ route('register') }}">إنشاء حساب جديد</a>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div class="toast" id="toast"></div>

    <script>
        // =========================================
        // LOGIN BUTTON INTERACTION
        // =========================================

        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const loginError = document.getElementById('loginError');
        const errorMessage = document.getElementById('errorMessage');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const usernameError = document.getElementById('usernameError');
        const passwordError = document.getElementById('passwordError');
        const toast = document.getElementById('toast');

        // ===== Toast Function =====
        function showToast(message, type = 'success') {
            toast.textContent = message;
            toast.className = 'toast ' + type;
            // Force reflow
            void toast.offsetWidth;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 4000);
        }



        // ===== Validate Username =====
        function validateUsername(username) {
            return username.length >= 3;
        }

        // ===== Validate Password =====
        function validatePassword(password) {
            return password.length >= 6;
        }

        // ===== Show Input Error =====
        function showInputError(element, errorElement) {
            element.style.borderColor = 'var(--danger-color)';
            errorElement.classList.add('show');
        }

        function hideInputError(element, errorElement) {
            element.style.borderColor = '';
            errorElement.classList.remove('show');
        }

        // ===== Real-time Validation =====
        usernameInput.addEventListener('input', function() {
            if (this.value.length > 0 && !validateUsername(this.value)) {
                showInputError(this, usernameError);
            } else {
                hideInputError(this, usernameError);
            }
        });

        passwordInput.addEventListener('input', function() {
            if (this.value.length > 0 && !validatePassword(this.value)) {
                showInputError(this, passwordError);
            } else {
                hideInputError(this, passwordError);
            }
        });

        // ===== Form Submit =====
        loginForm.addEventListener('submit', function(e) {
            // التحقق من صحة البيانات قبل الإرسال
            let isValid = true;



            // Validate Username
            if (!validateUsername(usernameInput.value)) {
                showInputError(usernameInput, usernameError);
                isValid = false;
            }

            if (!validatePassword(passwordInput.value)) {
                showInputError(passwordInput, passwordError);
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                showToast('يرجى تصحيح البيانات المطلوبة', 'error');
                return;
            }

            // ===== تفعيل حالة التحميل =====
            loginBtn.classList.add('loading');
            loginBtn.disabled = true;
            loginError.classList.remove('show');

            // سيتم إكمال الإرسال بواسطة Laravel
            // إذا نجح → ينتقل للـ Dashboard
            // إذا فشل → تظهر رسالة الخطأ
        });

        // ===== التعامل مع أخطاء Laravel =====
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                const errors = @json($errors->all());
                if (errors.length > 0) {
                    errorMessage.textContent = errors[0];
                    loginError.classList.add('show');
                    loginBtn.classList.remove('loading');
                    loginBtn.disabled = false;

                    // إظهار الخطأ في الحقل المناسب
                    if (errors[0].includes('اسم المستخدم') || errors[0].includes('username') || errors[0].includes('email')) {
                        showInputError(usernameInput, usernameError);
                    }
                    if (errors[0].includes('كلمة المرور') || errors[0].includes('password')) {
                        showInputError(passwordInput, passwordError);
                    }
                }
            });
        @endif

        // ===== إلغاء حالة التحميل عند العودة =====
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                loginBtn.classList.remove('loading');
                loginBtn.disabled = false;
            }
        });

        console.log('✅ Login page loaded successfully!');
    </script>

</body>
</html>