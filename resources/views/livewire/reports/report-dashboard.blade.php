<div>
    {{-- Header + Date Filter --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">لوحة التقارير</h2>
            <p class="text-sm text-gray-500 mt-1">نظرة عامة على المركز — بالليرة السورية</p>
        </div>
        <div class="flex items-center gap-3">
            <input type="date" wire:model.live="dateFrom"
                   class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200"
                   placeholder="من تاريخ">
            <span class="text-gray-400 text-sm">—</span>
            <input type="date" wire:model.live="dateTo"
                   class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200"
                   placeholder="إلى تاريخ">
            @if($dateFrom || $dateTo)
            <button wire:click="$set('dateFrom',''); $set('dateTo','')"
                    class="text-sm text-gray-400 hover:text-gray-600 px-2">✕ مسح</button>
            @endif
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500">إجمالي المرضى</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ number_format($totalPatients) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500">إجمالي الزيارات</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ number_format($totalVisits) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500">إجمالي الأطباء</p>
            <p class="text-3xl font-bold text-purple-600 mt-1">{{ number_format($totalDoctors) }}</p>
        </div>
    </div>

    {{-- Financial Summary (SYP only) --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500">إجمالي الإيرادات</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalRevenue) }}</p>
            <p class="text-xs text-gray-400 mt-1">ليرة سورية</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500">المحصَّل فعلياً</p>
            <p class="text-2xl font-bold text-green-700 mt-1">{{ number_format($totalPaid) }}</p>
            <p class="text-xs text-gray-400 mt-1">ليرة سورية</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500">صافي أرباح العيادة</p>
            <p class="text-2xl font-bold text-indigo-600 mt-1">{{ number_format($totalClinic) }}</p>
            <p class="text-xs text-gray-400 mt-1">ليرة سورية</p>
        </div>
    </div>

    {{-- Doctor Shares + Recent Visits --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 mb-4">حصص الأطباء</h3>
            @forelse($doctorShares as $doctor)
            <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                <span class="text-sm font-medium text-gray-600">{{ $doctor->name }}</span>
                <span class="font-bold text-gray-800">{{ number_format($doctor->total_share) }} <span class="text-xs text-gray-400">SYP</span></span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">لا توجد بيانات</p>
            @endforelse
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 mb-4">آخر الزيارات</h3>
            <div class="space-y-2">
                @forelse($recentVisits as $visit)
                <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $visit->patient_name }}</p>
                        <p class="text-xs text-gray-400">{{ $visit->doctor_name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-700">{{ number_format($visit->total_amount) }} <span class="text-xs text-gray-400">SYP</span></p>
                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($visit->created_at)->format('Y-m-d') }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">لا توجد زيارات</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
