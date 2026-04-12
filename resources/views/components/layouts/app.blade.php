<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'المركز الطبي' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-white border-l border-gray-100 shadow-sm flex flex-col">

            <!-- Logo -->
            <div class="px-6 py-5 border-b border-gray-100">
                <h1 class="text-lg font-bold text-blue-600">🏥 المركز الطبي</h1>
                <p class="text-xs text-gray-400 mt-0.5">نظام الإدارة</p>
            </div>

            <!-- Nav -->
            <nav class="flex-1 px-4 py-4 space-y-1">
                <a href="/patients"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                          {{ request()->is('patients*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span>👥</span> المرضى
                </a>
                <a href="/treatment-plans"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                          {{ request()->is('treatment-plans*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span>📋</span> خطط العلاج
                </a>
                <a href="/visits"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                          {{ request()->is('visits*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span>🏥</span> الزيارات
                </a>
                <a href="/appointments"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                          {{ request()->is('appointments*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span>📅</span> المواعيد
                </a>
                <a href="/payments"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                          {{ request()->is('payments*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span>💰</span> المدفوعات
                </a>

                @if(auth()->user()?->hasRole('admin'))
                <a href="/reports"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                          {{ request()->is('reports*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span>📊</span> التقارير
                </a>
                @endif
            </nav>

            <!-- User -->
            <div class="px-4 py-4 border-t border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ auth()->user()?->name }}</p>
                        <p class="text-xs text-gray-400">{{ auth()->user()?->getRoleNames()->first() }}</p>
                    </div>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit"
                                class="text-xs text-red-500 hover:text-red-700 font-medium transition">
                            خروج
                        </button>
                    </form>
                </div>
            </div>

        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 overflow-auto">
            {{ $slot }}
        </main>

    </div>

    @livewireScripts
    <script src="https://unpkg.com/livewire@3/dist/livewire.js"></script></body>
</html>