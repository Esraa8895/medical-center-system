<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">المواعيد</h2>
            <p class="text-sm text-gray-500 mt-1">إدارة مواعيد المركز</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex gap-3 mb-4">
        <input wire:model.live.debounce.300ms="search"
               type="text" placeholder="🔍 ابحثي باسم المريضة..."
               class="border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200 w-64">
        <select wire:model.live="status"
                class="border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200">
            <option value="">كل الحالات</option>
            <option value="scheduled">مجدول</option>
            <option value="completed">مكتمل</option>
            <option value="cancelled">ملغي</option>
        </select>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-right text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-600">#</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">المريضة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الطبيب</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الخدمة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">التاريخ</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الحالة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($appointments as $appointment)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-gray-400">{{ $appointment->id }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $appointment->patient_name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $appointment->doctor_name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $appointment->service_name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d H:i') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $appointment->status === 'scheduled' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ $appointment->status === 'scheduled' ? 'مجدول' : ($appointment->status === 'completed' ? 'مكتمل' : 'ملغي') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-400">لا يوجد مواعيد</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $appointments->links() }}</div>
    </div>
</div>