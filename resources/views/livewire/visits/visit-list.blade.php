<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">الزيارات</h2>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-right text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-600">#</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">المريضة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الطبيب</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">المبلغ الكلي</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">المدفوع</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">التاريخ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($visits as $visit)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-gray-400">{{ $visit->id }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $visit->patient?->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $visit->doctor?->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ number_format($visit->total_amount) }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ number_format($visit->paid_cost) }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $visit->created_at->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-400">لا يوجد زيارات</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $visits->links() }}</div>
    </div>
</div>