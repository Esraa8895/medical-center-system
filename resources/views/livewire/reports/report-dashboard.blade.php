<div>
    {{-- Header + Filters --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">لوحة التقارير</h2>
            <p class="text-sm text-gray-500 mt-1">نظرة عامة على المركز — بالليرة السورية</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            {{-- فلتر التخصص --}}
            <select wire:model.live="specialtyId"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2"
                    style="min-width:150px;">
                <option value="">🏥 كل التخصصات</option>
                @foreach($specialties as $sp)
                <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                @endforeach
            </select>
            {{-- فلتر التاريخ --}}
            <input type="date" wire:model.live="dateFrom"
                   class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2">
            <span class="text-gray-400 text-sm">—</span>
            <input type="date" wire:model.live="dateTo"
                   class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2">
            @if($dateFrom || $dateTo || $specialtyId)
            <button wire:click="$set('dateFrom',''); $set('dateTo',''); $set('specialtyId', null)"
                    class="text-sm text-gray-400 hover:text-gray-600 px-2">✕ مسح</button>
            @endif
        </div>
    </div>

    {{-- إحصائيات عامة --}}
    <div class="grid grid-cols-3 gap-4 mb-5">
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

    {{-- ملخص مالي --}}
    <div class="grid grid-cols-3 gap-4 mb-5">
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
            <p class="text-sm text-gray-500">حصة العيادة (قبل التكاليف)</p>
            <p class="text-2xl font-bold text-indigo-600 mt-1">{{ number_format($totalClinic) }}</p>
            <p class="text-xs text-gray-400 mt-1">ليرة سورية</p>
        </div>
    </div>

    {{-- تكاليف المركز + صافي الربح --}}
    <div class="grid grid-cols-2 gap-4 mb-5">
        <div class="rounded-xl border p-5" style="background:#fff7ed; border-color:#fed7aa;">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-lg">💸</span>
                <p class="text-sm font-semibold text-orange-700">تكاليف المركز</p>
            </div>
            <p class="text-2xl font-bold text-orange-800">{{ number_format($centerExpenses) }}</p>
            <p class="text-xs text-orange-400 mt-1">ليرة سورية — مواد وصيانة</p>
        </div>
        <div class="rounded-xl border p-5
            {{ $netProfit >= 0 ? '' : '' }}"
             style="{{ $netProfit >= 0 ? 'background:#f0fdf4; border-color:#bbf7d0;' : 'background:#fef2f2; border-color:#fecaca;' }}">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-lg">{{ $netProfit >= 0 ? '📈' : '📉' }}</span>
                <p class="text-sm font-semibold {{ $netProfit >= 0 ? 'text-green-700' : 'text-red-700' }}">صافي الربح الفعلي</p>
            </div>
            <p class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-green-800' : 'text-red-800' }}">
                {{ number_format(abs($netProfit)) }}
                @if($netProfit < 0)<span class="text-base">خسارة</span>@endif
            </p>
            <p class="text-xs mt-1 {{ $netProfit >= 0 ? 'text-green-400' : 'text-red-400' }}">حصة العيادة − التكاليف</p>
        </div>
    </div>

    {{-- حصص الأطباء + آخر الزيارات --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 mb-4">👨‍⚕️ حصص الأطباء</h3>
            @forelse($doctorShares as $doc)
            <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                <div>
                    <span class="text-sm font-medium text-gray-700">{{ $doc->name }}</span>
                    <span class="text-xs text-gray-400 mr-2">{{ $doc->percentage }}%</span>
                </div>
                <span class="font-bold text-gray-800 text-sm">{{ number_format($doc->total_share) }} <span class="text-xs text-gray-400">ل.س</span></span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-6">لا توجد بيانات</p>
            @endforelse
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 mb-4">🏥 آخر الزيارات</h3>
            @forelse($recentVisits as $visit)
            <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $visit->patient_name }}</p>
                    <p class="text-xs text-gray-400">{{ $visit->doctor_name }}
                        @if(isset($visit->specialty_name))
                        · <span class="text-purple-500">{{ $visit->specialty_name }}</span>
                        @endif
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-gray-700">{{ number_format($visit->total_amount) }} <span class="text-xs text-gray-400">ل.س</span></p>
                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($visit->created_at)->format('Y-m-d') }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-6">لا توجد زيارات</p>
            @endforelse
        </div>
    </div>
</div>
