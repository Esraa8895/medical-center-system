<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">المواعيد</h2>
            <p class="text-sm text-gray-500 mt-1">إدارة مواعيد المركز</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3 mb-4">
        {{-- Search --}}
        <div class="relative">
            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
            <input wire:model.live.debounce.300ms="search"
                   type="text" placeholder="ابحثي باسم المريضة..."
                   class="border border-gray-200 rounded-xl pr-9 pl-4 py-2.5 text-sm focus:outline-none focus:ring-2 w-56">
        </div>

        {{-- Specialty Filter --}}
        <select wire:model.live="specialtyId"
                class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2"
                style="min-width: 160px;">
            <option value="">🏥 كل التخصصات</option>
            @foreach($specialties as $specialty)
            <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
            @endforeach
        </select>

        {{-- Status Filter --}}
        <select wire:model.live="status"
                class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2"
                style="min-width: 140px;">
            <option value="">كل الحالات</option>
            <option value="scheduled">مجدول</option>
            <option value="completed">مكتمل</option>
            <option value="cancelled">ملغي</option>
        </select>

        {{-- Active Filter Badge --}}
        @if($specialtyId)
        <div class="flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium"
             style="background: #f5f0fa; color: #511269;">
            <span>تخصص: {{ $specialties->firstWhere('id', $specialtyId)?->name }}</span>
            <button wire:click="$set('specialtyId', '')" class="mr-1 hover:opacity-70">✕</button>
        </div>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-right text-sm">
            <thead style="background: #f5f0fa;">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-600">#</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">المريضة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الطبيب / التخصص</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الخدمة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">التاريخ</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الحالة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($appointments as $appointment)
                <tr class="hover:bg-purple-50/30 transition">
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">{{ $appointment->id }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $appointment->patient_name }}</td>
                    <td class="px-6 py-4">
                        <div class="text-gray-800 font-medium">{{ $appointment->doctor_name }}</div>
                        <div class="text-xs mt-0.5 px-2 py-0.5 rounded-full inline-block"
                             style="background: #ede9fe; color: #6d28d9;">
                            {{ $appointment->specialty_name }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $appointment->service_name }}</td>
                    <td class="px-6 py-4 text-gray-600 text-xs font-mono">
                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}
                        <div class="text-gray-400">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $appointment->status === 'scheduled' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ $appointment->status === 'scheduled' ? '🕐 مجدول' : ($appointment->status === 'completed' ? '✅ مكتمل' : '❌ ملغي') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-4xl">📅</span>
                            <p class="font-medium">لا توجد مواعيد
                            @if($specialtyId || $search || $status) مطابقة للفلاتر المحددة @endif
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $appointments->links() }}</div>
    </div>
</div>
