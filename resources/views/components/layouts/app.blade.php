<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'عيادات شهاب' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-56 flex flex-col flex-shrink-0"
               style="background: linear-gradient(180deg, #3a0a52 0%, #511269 60%, #6b1a85 100%);">

            <!-- Logo -->
            <div class="px-4 py-5 text-center border-b" style="border-color: rgba(255,255,255,0.1);">
                <img src="/images/logo55.png"
                     alt="عيادات شهاب التخصصية"
                     class="mx-auto object-contain"
                     style="max-height: 80px; max-width: 140px;">
            </div>

            <!-- Nav -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

                {{-- ── الكل ── --}}
                @php
                    $navLink = fn($path, $icon, $label) =>
                        '<a href="/'.$path.'" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 '.
                        (request()->is($path.'*') || ($path === 'patients' && (request()->is('/') || request()->is('patients*'))) ? 'text-white' : 'hover:text-white').'" '.
                        'style="'.(request()->is($path.'*') || ($path === 'patients' && (request()->is('/') || request()->is('patients*'))) ? 'background: rgba(255,255,255,0.18);' : 'color: rgba(220,180,255,0.85);').'">'.
                        '<span class="text-base">'.$icon.'</span><span>'.$label.'</span></a>';
                @endphp

                <a href="/patients"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                          {{ request()->is('patients*') || request()->is('/') ? 'text-white' : 'hover:text-white' }}"
                   style="{{ request()->is('patients*') || request()->is('/') ? 'background: rgba(255,255,255,0.18);' : 'color: rgba(220,180,255,0.85);' }}">
                    <span class="text-base">👥</span><span>المرضى</span>
                </a>

                <a href="/treatment-plans"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                          {{ request()->is('treatment-plans*') ? 'text-white' : 'hover:text-white' }}"
                   style="{{ request()->is('treatment-plans*') ? 'background: rgba(255,255,255,0.18);' : 'color: rgba(220,180,255,0.85);' }}">
                    <span class="text-base">📋</span><span>خطط العلاج</span>
                </a>

                <a href="/appointments"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                          {{ request()->is('appointments*') ? 'text-white' : 'hover:text-white' }}"
                   style="{{ request()->is('appointments*') ? 'background: rgba(255,255,255,0.18);' : 'color: rgba(220,180,255,0.85);' }}">
                    <span class="text-base">📅</span><span>المواعيد</span>
                </a>

                <a href="/visits"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                          {{ request()->is('visits*') ? 'text-white' : 'hover:text-white' }}"
                   style="{{ request()->is('visits*') ? 'background: rgba(255,255,255,0.18);' : 'color: rgba(220,180,255,0.85);' }}">
                    <span class="text-base">🏥</span><span>الزيارات</span>
                </a>

                <a href="/payments"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                          {{ request()->is('payments*') ? 'text-white' : 'hover:text-white' }}"
                   style="{{ request()->is('payments*') ? 'background: rgba(255,255,255,0.18);' : 'color: rgba(220,180,255,0.85);' }}">
                    <span class="text-base">💳</span><span>المدفوعات</span>
                </a>

                {{-- ── Admin فقط ── --}}
                @if(auth()->user()?->hasRole('admin'))
                <div class="pt-2 mt-2" style="border-top: 1px solid rgba(255,255,255,0.1);">
                    <p class="px-3 pb-1 text-xs font-semibold uppercase tracking-wider"
                       style="color: rgba(184,150,12,0.8);">إدارة</p>

                    <a href="/daily"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                              {{ request()->is('daily*') ? 'text-white' : 'hover:text-white' }}"
                       style="{{ request()->is('daily*') ? 'background: rgba(255,255,255,0.18);' : 'color: rgba(220,180,255,0.85);' }}">
                        <span class="text-base">📊</span><span>اليومية</span>
                    </a>

                    <a href="/reports"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                              {{ request()->is('reports*') ? 'text-white' : 'hover:text-white' }}"
                       style="{{ request()->is('reports*') ? 'background: rgba(255,255,255,0.18);' : 'color: rgba(220,180,255,0.85);' }}">
                        <span class="text-base">📈</span><span>التقارير</span>
                    </a>

                    <a href="/expenses"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                              {{ request()->is('expenses*') ? 'text-white' : 'hover:text-white' }}"
                       style="{{ request()->is('expenses*') ? 'background: rgba(255,255,255,0.18);' : 'color: rgba(220,180,255,0.85);' }}">
                        <span class="text-base">💰</span><span>تكاليف المركز</span>
                    </a>

                    <a href="/users"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                              {{ request()->is('users*') ? 'text-white' : 'hover:text-white' }}"
                       style="{{ request()->is('users*') ? 'background: rgba(255,255,255,0.18);' : 'color: rgba(220,180,255,0.85);' }}">
                        <span class="text-base">⚙️</span><span>إدارة الحسابات</span>
                    </a>
                </div>
                @endif

            </nav>

            <!-- User Info + Logout -->
            <div class="px-4 py-4" style="border-top: 1px solid rgba(255,255,255,0.1);">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0"
                         style="background: rgba(184,150,12,0.3); color: #D4AF37; border: 1px solid rgba(184,150,12,0.4);">
                        {{ mb_substr(auth()->user()?->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()?->name }}</p>
                        <p class="text-xs truncate" style="color: #D4AF37;">
                            {{ auth()->user()?->hasRole('admin') ? 'مدير' : 'موظف استقبال' }}
                        </p>
                    </div>
                </div>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit"
                            class="w-full text-xs py-2 rounded-lg font-medium transition-all duration-150 text-center"
                            style="background: rgba(220,38,38,0.15); color: #fca5a5; border: 1px solid rgba(220,38,38,0.2);"
                            onmouseover="this.style.background='rgba(220,38,38,0.3)'"
                            onmouseout="this.style.background='rgba(220,38,38,0.15)'">
                        🚪 تسجيل الخروج
                    </button>
                </form>
            </div>

        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 overflow-auto min-w-0">
            {{ $slot }}
        </main>

    </div>

    @livewireScripts
</body>
</html>
