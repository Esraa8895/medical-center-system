<div>

    <h1 class="text-2xl font-bold mb-6">
        💰 المدفوعات المؤرشفة
    </h1>

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <table class="w-full text-right">

            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4">المريضة</th>
                    <th class="p-4">رقم الزيارة</th>
                    <th class="p-4">المبلغ</th>
                    <th class="p-4">طريقة الدفع</th>
                    <th class="p-4">تاريخ الأرشفة</th>
                </tr>
            </thead>

            <tbody>

                @forelse($payments as $payment)

                <tr class="border-t">

                    <td class="p-4">
                        {{ $payment->patient?->name }}
                    </td>

                    <td class="p-4">
                        #{{ $payment->visit_id }}
                    </td>

                    <td class="p-4">
                        {{ $payment->amount }}
                    </td>

                    <td class="p-4">
                        {{ $payment->payment_method }}
                    </td>

                    <td class="p-4 text-gray-500">
                        {{ $payment->deleted_at }}
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="5" class="p-10 text-center text-gray-400">
                        لا يوجد مدفوعات مؤرشفة
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-5">
        {{ $payments->links() }}
    </div>

</div>
