<div>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">اليومية</h2>
            <p class="text-sm text-gray-500 mt-1">تفاصيل زيارات وإيرادات اليوم</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-500">التاريخ</label>
            <input wire:model.live="date" type="date"
                   class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
        </div>
        <select wire:model.live="specialtyId"
                class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2"
                style="min-width:160px;">
            <option value="">🏥 كل التخصصات</option>
            @foreach($specialties as $sp)
            <option value="{{ $sp->id }}">{{ $sp->name }}</option>
            @endforeach
        </select>
        @if($specialtyId)
        <button wire:click="$set('specialtyId',null)"
                class="px-3 py-2 rounded-xl text-xs text-gray-500 border border-gray-200 hover:bg-gray-50">
            مسح ✕
        </button>
        @endif
    </div>

    {{-- بطاقات الإجمالي --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="px-5 py-4 rounded-xl border" style="background:#f0fdf4; border-color:#bbf7d0;">
            <p class="text-xs text-gray-500 mb-1">عدد الجلسات</p>
            <p class="text-2xl font-bold" style="color:#166534;">{{ $dayCount }}</p>
        </div>
        <div class="px-5 py-4 rounded-xl border" style="background:#eff6ff; border-color:#bfdbfe;">
            <p class="text-xs text-gray-500 mb-1">إجمالي الفواتير</p>
            <p class="text-xl font-bold" style="color:#1a56a0;">{{ number_format($dayTotal) }} <span class="text-sm font-normal text-gray-400">ل.س</span></p>
        </div>
        <div class="px-5 py-4 rounded-xl border" style="background:#f0fdf4; border-color:#bbf7d0;">
            <p class="text-xs text-gray-500 mb-1">المحصّل</p>
            <p class="text-xl font-bold" style="color:#166534;">{{ number_format($dayPaid) }} <span class="text-sm font-normal text-gray-400">ل.س</span></p>
        </div>
        <div class="px-5 py-4 rounded-xl border" style="background:#fff7ed; border-color:#fed7aa;">
            <p class="text-xs text-gray-500 mb-1">المتبقي</p>
            <p class="text-xl font-bold" style="color:#9a3412;">{{ number_format($dayRemaining) }} <span class="text-sm font-normal text-gray-400">ل.س</span></p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-right text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-4 font-semibold text-gray-600">المريضة</th>
                    <th class="px-5 py-4 font-semibold text-gray-600">الخدمة</th>
                    <th class="px-5 py-4 font-semibold text-gray-600">الطبيب</th>
                    <th class="px-5 py-4 font-semibold text-gray-600">التخصص</th>
                    <th class="px-5 py-4 font-semibold text-gray-600">الكلي</th>
                    <th class="px-5 py-4 font-semibold text-gray-600">الحسم</th>
                    <th class="px-5 py-4 font-semibold text-gray-600">المدفوع</th>
                    <th class="px-5 py-4 font-semibold text-gray-600">المتبقي</th>
                    <th class="px-5 py-4 font-semibold text-gray-600">الوقت</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($rows as $row)
                <tr class="hover:bg-blue-50/20 transition">
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $row->patient_name }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $row->service_name }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $row->doctor_name }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium"
                              style="background:#dbeafe; color:#1a56a0;">
                            {{ $row->specialty_name }}
                        </span>
                    </td>
                    <td class="px-5 py-3 font-semibold text-gray-800">{{ number_format($row->total) }}</td>
                    <td class="px-5 py-3 text-orange-500">
                        {{ $row->discount > 0 ? number_format($row->discount) : '—' }}
                    </td>
                    <td class="px-5 py-3 font-semibold text-green-600">{{ number_format($row->paid) }}</td>
                    <td class="px-5 py-3">
                        @if($row->remaining > 0)
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-600">
                            {{ number_format($row->remaining) }}
                        </span>
                        @else
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-600">
                            مسدد ✅
                        </span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-400 text-xs font-mono">
                        {{ \Carbon\Carbon::parse($row->visit_time)->format('H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-16 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-4xl">📋</span>
                            <p class="font-medium">لا توجد زيارات
                                @if($specialtyId) لهذا التخصص @endif
                                بتاريخ {{ $date }}
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($rows->count() > 0)
            <tfoot>
                <tr style="background:#f8fafc; border-top:2px solid #e2e8f0;">
                    <td colspan="4" class="px-5 py-3 font-bold text-gray-700">المجموع</td>
                    <td class="px-5 py-3 font-bold text-gray-800">{{ number_format($dayTotal) }}</td>
                    <td class="px-5 py-3"></td>
                    <td class="px-5 py-3 font-bold text-green-600">{{ number_format($dayPaid) }}</td>
                    <td class="px-5 py-3 font-bold text-red-500">{{ number_format($dayRemaining) }}</td>
                    <td class="px-5 py-3"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

</div>
