<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">المدفوعات</h2>
            <p class="text-sm text-gray-500 mt-1">سجل المدفوعات</p>
        </div>
    </div>

    <!-- Totals -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        @foreach($totals as $total)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500">إجمالي {{ $total->currency }}</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($total->total) }}</p>
        </div>
        @endforeach
    </div>

    <!-- Filter -->
    <div class="mb-4">
        <select wire:model.live="currency"
                class="border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200">
            <option value="">كل العملات</option>
            <option value="SYP">SYP</option>
            <option value="USD">USD</option>
            <option value="TRY">TRY</option>
        </select>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-right text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-600">#</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">المريضة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">المبلغ</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">العملة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">ملاحظات</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">التاريخ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-gray-400">{{ $payment->id }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $payment->patient_name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ number_format($payment->amount) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">
                            {{ $payment->currency }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $payment->notes ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($payment->created_at)->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-400">لا يوجد مدفوعات</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $payments->links() }}</div>
    </div>
</div>