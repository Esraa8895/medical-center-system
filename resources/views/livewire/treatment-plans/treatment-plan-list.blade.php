<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">خطط العلاج</h2>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-right text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-600">#</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">المريضة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الطبيب</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الخدمة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الجلسات</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الحالة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($plans as $plan)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-gray-400">{{ $plan->id }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $plan->patient?->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $plan->doctor?->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $plan->service?->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $plan->completed_sessions }}/{{ $plan->total_sessions ?? '∞' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $plan->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $plan->status === 'completed' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $plan->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ $plan->status === 'active' ? 'نشطة' : ($plan->status === 'completed' ? 'مكتملة' : 'ملغية') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-400">لا يوجد خطط علاج</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $plans->links() }}</div>
    </div>
</div>