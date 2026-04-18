<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">المدفوعات</h2>
            <p class="text-sm text-gray-500 mt-1">سجل المدفوعات — بالليرة السورية</p>
        </div>
    </div>

    {{-- Total Paid --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-6 inline-block min-w-[220px]">
        <p class="text-sm text-gray-500">إجمالي المحصَّل</p>
        <p class="text-2xl font-bold text-green-700 mt-1">{{ number_format($totalPaid) }} <span class="text-xs text-gray-400 font-normal">SYP</span></p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-right text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-600">#</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">المريضة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">المبلغ (SYP)</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">سعر الصرف</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">ملاحظات</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">التاريخ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-gray-400">{{ $payment->id }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $payment->patient_name }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ number_format($payment->amount) }}</td>
                    <td class="px-6 py-4 text-gray-500">
                        @if($payment->exchange_rate != 1)
                            <span class="text-xs bg-amber-50 text-amber-700 px-2 py-1 rounded-full">
                                {{ number_format($payment->exchange_rate, 2) }}
                            </span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
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
