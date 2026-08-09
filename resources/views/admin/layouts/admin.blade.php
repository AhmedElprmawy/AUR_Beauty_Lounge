<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AUR - Admin Panel')</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
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
            --whatsapp-color: #25D366;
            --info-color: #00bcd4;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 260px;
            background: var(--surface-dark);
            border-left: 1px solid var(--border-color);
            padding: 20px;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            overflow-y: auto;
            z-index: 100;
        }

        .admin-sidebar .logo {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 20px;
        }

        .admin-sidebar .logo img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 2px solid var(--primary-gold);
        }

        .admin-sidebar .logo h2 {
            color: var(--primary-gold);
            font-size: 18px;
            margin-top: 10px;
        }

        .admin-sidebar nav ul {
            list-style: none;
            padding: 0;
        }

        .admin-sidebar nav ul li {
            margin-bottom: 5px;
        }

        .admin-sidebar nav ul li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 600;
        }

        .admin-sidebar nav ul li a:hover,
        .admin-sidebar nav ul li a.active {
            background: var(--surface-light);
            color: var(--primary-gold);
        }

        .admin-sidebar nav ul li a i {
            width: 20px;
            height: 20px;
        }

        .admin-sidebar nav ul li a .nav-icon {
            font-size: 20px;
        }

        /* Main Content */
        .admin-main {
            flex: 1;
            margin-right: 260px;
            padding: 30px;
            min-height: 100vh;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .admin-header h1 {
            color: var(--primary-gold);
            font-size: 24px;
            margin: 0;
        }

        .admin-header .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--text-muted);
        }

        .admin-header .user-info .logout-btn {
            color: var(--danger-color);
            text-decoration: none;
            font-weight: 600;
        }

        .admin-header .user-info .logout-btn:hover {
            text-decoration: underline;
        }

        /* Table */
        .table-container {
            background: var(--surface-dark);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-container th {
            background: var(--bg-dark);
            color: var(--primary-gold);
            padding: 14px 16px;
            text-align: right;
            font-weight: 800;
            border-bottom: 2px solid var(--border-color);
        }

        .table-container td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .table-container tr:hover td {
            background: rgba(212, 175, 55, 0.03);
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
            transition: transform 0.4s;
            font-family: 'Cairo', sans-serif;
        }

        .toast.show {
            transform: translateX(-50%) translateY(0);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 70px;
                padding: 15px 10px;
            }

            .admin-sidebar .logo h2,
            .admin-sidebar nav ul li a span {
                display: none;
            }

            .admin-sidebar nav ul li a {
                justify-content: center;
                padding: 12px;
            }

            .admin-main {
                margin-right: 70px;
                padding: 15px;
            }

            .admin-header {
                flex-direction: column;
                text-align: center;
            }
        }

        @media print {
            .admin-sidebar,
            .admin-header {
                display: none !important;
            }
            .admin-main {
                margin-right: 0 !important;
                padding: 0 !important;
            }
        }
         /* ===== Notification Bell ===== */
        .notif-bell {
            position: relative;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: 0.3s;
            color: var(--text-muted);
            font-size: 22px;
        }

        .notif-bell:hover {
            background: rgba(212, 175, 55, 0.1);
            color: var(--primary-gold);
        }

        .notif-bell .badge {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--danger-color);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 50%;
            min-width: 20px;
            text-align: center;
            border: 2px solid var(--bg-dark);
            display: none;
        }

        .notif-bell .badge.show {
            display: block;
        }

        /* ===== Notifications Dropdown ===== */
        .notif-dropdown {
            position: absolute;
            top: 55px;
            left: 0;
            width: 380px;
            max-height: 500px;
            background: var(--surface-dark);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.5);
            z-index: 9999;
            display: none;
            overflow: hidden;
            direction: rtl;
        }

        .notif-dropdown.show {
            display: block;
        }

        .notif-dropdown .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .notif-dropdown .header h3 {
            color: var(--text-main);
            font-size: 16px;
            font-weight: 700;
            margin: 0;
        }

        .notif-dropdown .header .mark-all {
            color: var(--primary-gold);
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
        }

        .notif-dropdown .header .mark-all:hover {
            color: var(--dark-gold);
        }

        .notif-dropdown .list {
            max-height: 400px;
            overflow-y: auto;
            padding: 5px 0;
        }

        .notif-dropdown .list::-webkit-scrollbar {
            width: 4px;
        }

        .notif-dropdown .list::-webkit-scrollbar-track {
            background: var(--bg-dark);
        }

        .notif-dropdown .list::-webkit-scrollbar-thumb {
            background: var(--primary-gold);
            border-radius: 4px;
        }

        .notif-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 20px;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            color: var(--text-main);
        }

        .notif-item:hover {
            background: rgba(212, 175, 55, 0.05);
        }

        .notif-item.unread {
            border-right: 3px solid var(--primary-gold);
            background: rgba(212, 175, 55, 0.03);
        }

        .notif-item .icon {
            font-size: 20px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .notif-item .content {
            flex: 1;
            min-width: 0;
        }

        .notif-item .content .title {
            font-weight: 600;
            font-size: 14px;
            color: var(--text-main);
        }

        .notif-item .content .subtitle {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .notif-item .content .time {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .notif-item .badge-new {
            background: var(--primary-gold);
            color: #000;
            font-size: 9px;
            font-weight: 700;
            padding: 1px 8px;
            border-radius: 10px;
            flex-shrink: 0;
        }

        .notif-empty {
            padding: 40px 20px;
            text-align: center;
            color: var(--text-muted);
        }

        .notif-empty .icon {
            font-size: 40px;
            display: block;
            margin-bottom: 10px;
        }

        /* ===== Top Bar ===== */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 25px;
        }

        .top-bar .right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .top-bar .user-info {
            color: var(--text-muted);
            font-size: 14px;
        }

        .top-bar .user-info strong {
            color: var(--text-main);
        }

        /* ===== Mobile ===== */
        @media (max-width: 768px) {
            .notif-dropdown {
                width: 320px;
                left: -100px;
            }
        }

        @stack('styles')
    </style>
</head>
<body>


    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="logo">
                <img src="{{ asset('images/logo.jpeg') }}" alt="AUR">
                <h2>AUR Beauty</h2>
            </div>
            <nav>
                <ul>
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <span class="nav-icon">📊</span>
                            <span>لوحة التحكم</span>
                        </a>
                    </li>
                    

            
<li>
    <a href="{{ route('admin.bookings.index') }}" class="{{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
        <span class="nav-icon">📅</span>
        <span>الحجوزات</span>
        
        @php $pendingCount = \App\Models\Booking::getPendingCount(); @endphp
        @if($pendingCount > 0)
            <span style="margin-right: auto; background: var(--primary-gold); color: #000; padding: 1px 10px; border-radius: 10px; font-size: 11px; font-weight: 700;">
                {{ $pendingCount }}
            </span>
        @endif
    </a>
</li>
                    <li>
                        <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services*') ? 'active' : '' }}">
                            <span class="nav-icon">📋</span>
                            <span>الخدمات</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.bridal.index') }}" class="{{ request()->routeIs('admin.bridal*') ? 'active' : '' }}">
                            <span class="nav-icon">👰</span>
                            <span>العرائس</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.packages.index') }}" class="{{ request()->routeIs('admin.packages*') ? 'active' : '' }}">
                            <span class="nav-icon">💎</span>
                            <span>باقات العرائس</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.transformations.index') }}" class="{{ request()->routeIs('admin.transformations*') ? 'active' : '' }}">
                            <span class="nav-icon">🔄</span>
                            <span>قبل وبعد</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.videos.index') }}" class="{{ request()->routeIs('admin.videos*') ? 'active' : '' }}">
                            <span class="nav-icon">🎥</span>
                            <span>فيديوهات</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.gallery.index') }}" class="{{ request()->routeIs('admin.gallery*') ? 'active' : '' }}">
                            <span class="nav-icon">🖼️</span>
                            <span>معرض الأعمال</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.staff.index') }}" class="{{ request()->routeIs('admin.staff*') ? 'active' : '' }}">
                            <span class="nav-icon">👤</span>
                            <span>فريق العمل</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.testimonials.index') }}" class="{{ request()->routeIs('admin.testimonials*') ? 'active' : '' }}">
                            <span class="nav-icon">💬</span>
                            <span>آراء العملاء</span>
                        </a>
                    </li>
                   
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
    <h1 style="margin: 0;">@yield('page-title', 'لوحة التحكم')</h1>

    <!-- الجزء الجديد اللي هنحطه مع زر الخروج -->
    <div style="display: flex; align-items: center; gap: 20px;">
        
        <!-- الإشعارات -->
        <div class="notif-container" style="position: relative;">
            <div class="notif-bell" onclick="toggleNotifications()" id="notificationBell">
                <i data-lucide="bell" style="width: 24px; height: 24px; color: var(--text-muted);"></i>
                <span class="badge" id="notifBadge">0</span>
            </div>
            
            {{-- Dropdown الإشعارات --}}
            <div class="notif-dropdown" id="notifDropdown">
                <div class="header">
                    <h3>🔔 الإشعارات</h3>
                    <a href="#" class="mark-all" onclick="markAllAsRead(event)">تحديد الكل كمقروء</a>
                </div>
                <div class="list" id="notifList">
                    <div class="notif-empty">
                        <span class="icon">📭</span>
                        <p>لا توجد إشعارات</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- زر تسجيل الخروج -->
        <div class="user-info" style="display: flex; align-items: center;">
            <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="logout-btn">تسجيل الخروج</button>
            </form>
        </div>
    </div>
</div>
            @if(session('success'))
                <div style="background: rgba(76, 175, 80, 0.15); color: var(--success-color); padding: 15px 20px; border-radius: 8px; border-right: 4px solid var(--success-color); margin-bottom: 20px;">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background: rgba(255, 77, 77, 0.15); color: var(--danger-color); padding: 15px 20px; border-radius: 8px; border-right: 4px solid var(--danger-color); margin-bottom: 20px;">
                    ⚠️ {{ $errors->first() }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Toast -->
    <div class="toast" id="toast"></div>

    <script>
         let notifInterval;

        // ==========================================
        // تبديل عرض الإشعارات
        // ==========================================
        function toggleNotifications() {
            const dropdown = document.getElementById('notifDropdown');
            dropdown.classList.toggle('show');
            if (dropdown.classList.contains('show')) {
                loadNotifications();
            }
        }

        // ==========================================
        // تحميل الإشعارات
        // ==========================================
        function loadNotifications() {
            fetch('{{ route("admin.notifications.unread") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderNotifications(data.notifications, data.count);
                    updateBadge(data.count);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // ==========================================
        // عرض الإشعارات
        // ==========================================
        function renderNotifications(notifications, count) {
            const list = document.getElementById('notifList');
            const badge = document.getElementById('notifBadge');

            if (count === 0) {
                list.innerHTML = `
                    <div class="notif-empty">
                        <span class="icon">🎉</span>
                        <p>كل شيء هادئ! لا توجد إشعارات جديدة</p>
                    </div>
                `;
                badge.classList.remove('show');
                return;
            }

            let html = '';
            notifications.forEach(notif => {
                const isUnread = !notif.read_at;
                const icon = notif.type === 'booking_reminder' ? '🔔' : '📌';
                
                html += `
                    <div class="notif-item ${isUnread ? 'unread' : ''}" onclick="markAsRead('${notif.id}')">
                        <span class="icon">${icon}</span>
                        <div class="content">
                            <div class="title">${notif.customer_name}</div>
                            <div class="subtitle">${notif.service_name} - ${notif.date} ${notif.time || ''}</div>
                            <div class="time">${notif.created_at}</div>
                        </div>
                        ${isUnread ? '<span class="badge-new">جديد</span>' : ''}
                    </div>
                `;
            });

            list.innerHTML = html;
            badge.textContent = count;
            badge.classList.add('show');
        }

        // ==========================================
        // تحديث عدد الإشعارات
        // ==========================================
        function updateBadge(count) {
            const badge = document.getElementById('notifBadge');
            if (count > 0) {
                badge.textContent = count;
                badge.classList.add('show');
            } else {
                badge.classList.remove('show');
            }
        }

        // ==========================================
        // تحديث إشعار إلى مقروء
        // ==========================================
        function markAsRead(id) {
            fetch(`/admin/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                    // تحديث العدد في الجرس
                    fetchUnreadCount();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // ==========================================
        // تحديث جميع الإشعارات إلى مقروءة
        // ==========================================
        function markAllAsRead(e) {
            e.preventDefault();
            
            if (!confirm('هل أنت متأكد من تحديث جميع الإشعارات كمقروءة؟')) return;

            fetch('{{ route("admin.notifications.read-all") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                    fetchUnreadCount();
                    showToast('✅ تم تحديث جميع الإشعارات');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // ==========================================
        // جلب عدد الإشعارات غير المقروءة
        // ==========================================
        function fetchUnreadCount() {
            fetch('{{ route("admin.notifications.unread") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateBadge(data.count);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // ==========================================
        // إغلاق الـ Dropdown عند الضغط خارجها
        // ==========================================
        document.addEventListener('click', function(e) {
            const container = document.querySelector('.notif-container');
            if (container && !container.contains(e.target)) {
                document.getElementById('notifDropdown').classList.remove('show');
            }
        });

        // ==========================================
        // تحميل الإشعارات عند فتح الصفحة
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            fetchUnreadCount();
            
            // تحديث كل 60 ثانية
            notifInterval = setInterval(fetchUnreadCount, 60000);
        });

        // ==========================================
        // Toast
        // ==========================================
        function showToast(msg) {
            let toast = document.getElementById('toast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'toast';
                document.body.appendChild(toast);
            }
            toast.textContent = msg;
            toast.classList.add('show');
            clearTimeout(toast._timeout);
            toast._timeout = setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // Lucide Icons
        lucide.createIcons();

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.style.background = type === 'error' ? 'rgba(255, 77, 77, 0.95)' : 'var(--primary-gold)';
            toast.style.color = type === 'error' ? '#fff' : '#000';
            toast.classList.add('show');
            clearTimeout(window.toastTimeout);
            window.toastTimeout = setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        @stack('scripts')
    </script>
</body>
</html>