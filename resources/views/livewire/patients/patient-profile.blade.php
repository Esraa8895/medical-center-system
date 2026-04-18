<div>
    <!-- Back -->
    <div class="mb-6">
        <a href="/patients" class="text-sm font-medium flex items-center gap-2 w-fit" style="color: #511269;">
            ← العودة لقائمة المرضى
        </a>
    </div>

    <!-- Header Card -->
    <div class="rounded-2xl p-6 mb-6 text-white" style="background: linear-gradient(135deg, #511269, #7B2D8B);">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold"
                 style="background: rgba(255,255,255,0.2);">
                {{ mb_substr($patient->name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold">{{ $patient->name }}</h2>
                <div class="flex gap-4 mt-1 text-purple-200 text-sm">
                    @if($patient->phone)
                    <span>📞 {{ $patient->phone }}</span>
                    @endif
                    @if($patient->age)
                    <span>🎂 {{ $patient->age }} سنة</span>
                    @endif
                </div>
                @if($patient->previous_diseases)
                <p class="text-purple-200 text-xs mt-1">الأمراض السابقة: {{ $patient->previous_diseases }}</p>
                @endif
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 mt-6">
            <div class="rounded-xl p-3 text-center" style="background: rgba(255,255,255,0.15);">
                <p class="text-2xl font-bold">{{ count($treatmentPlans) }}</p>
                <p class="text-xs text-purple-200 mt-0.5">خطط العلاج</p>
            </div>
            <div class="rounded-xl p-3 text-center" style="background: rgba(255,255,255,0.15);">
                <p class="text-2xl font-bold">{{ count($visits) }}</p>
                <p class="text-xs text-purple-200 mt-0.5">الزيارات</p>
            </div>
            <div class="rounded-xl p-3 text-center" style="background: rgba(255,255,255,0.15);">
                <p class="text-2xl font-bold">{{ number_format($totalPaid) }}</p>
                <p class="text-xs text-purple-200 mt-0.5">إجمالي المدفوع</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">

        <!-- Treatment Plans -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span>📋</span> خطط العلاج
            </h3>
            @forelse($treatmentPlans as $plan)
            <div class="border border-gray-100 rounded-lg p-3 mb-3 last:mb-0">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium text-gray-800 text-sm">{{ $plan->service_name }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $plan->doctor_name }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full
                        {{ $plan->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $plan->status === 'completed' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $plan->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ $plan->status === 'active' ? 'نشطة' : ($plan->status === 'completed' ? 'مكتملة' : 'ملغية') }}
                    </span>
                </div>
                <div class="mt-2">
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                        <span>الجلسات: {{ $plan->completed_sessions }}/{{ $plan->total_sessions ?? '∞' }}</span>
                        <span>{{ number_format($plan->expected_total) }}</span>
                    </div>
                    @if($plan->total_sessions)
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full" style="background: #511269; width: {{ min(100, ($plan->completed_sessions / $plan->total_sessions) * 100) }}%"></div>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-gray-400 text-sm text-center py-4">لا يوجد خطط علاج</p>
            @endforelse
        </div>

        <!-- Payments -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span>💰</span> المدفوعات
            </h3>
            <div class="flex justify-between items-center mb-3 p-3 rounded-lg" style="background: #f5f0fa;">
                <span class="text-sm font-medium" style="color: #511269;">إجمالي المدفوع</span>
                <span class="font-bold" style="color: #511269;">{{ number_format($totalPaid) }}</span>
            </div>
            @forelse($payments as $payment)
            <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ number_format($payment->amount) }}</p>
                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($payment->created_at)->format('Y-m-d') }}</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full bg-purple-100 text-purple-700">
                    {{ $payment->currency }}
                </span>
            </div>
            @empty
            <p class="text-gray-400 text-sm text-center py-4">لا يوجد مدفوعات</p>
            @endforelse
        </div>

    </div>

    <!-- Visits -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mt-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span>🏥</span> الزيارات
        </h3>
        <table class="w-full text-right text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="pb-3 font-semibold text-gray-600">#</th>
                    <th class="pb-3 font-semibold text-gray-600">الطبيب</th>
                    <th class="pb-3 font-semibold text-gray-600">المبلغ الكلي</th>
                    <th class="pb-3 font-semibold text-gray-600">المدفوع</th>
                    <th class="pb-3 font-semibold text-gray-600">التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visits as $visit)
                <tr class="border-b border-gray-50 last:border-0">
                    <td class="py-3 text-gray-400">{{ $visit->id }}</td>
                    <td class="py-3 text-gray-700">{{ $visit->doctor_name }}</td>
                    <td class="py-3 text-gray-700">{{ number_format($visit->total_amount) }}</td>
                    <td class="py-3 text-gray-700">{{ number_format($visit->paid_cost) }}</td>
                    <td class="py-3 text-gray-400">{{ \Carbon\Carbon::parse($visit->created_at)->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-400">لا يوجد زيارات</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
