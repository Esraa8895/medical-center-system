<div>

    <h1 class="text-2xl font-bold mb-6">
         الزيارات المؤرشفة
    </h1>

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <table class="w-full text-right">

            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4">المريضة</th>
                    <th class="p-4">الطبيب</th>
                    <th class="p-4">المبلغ</th>
                    <th class="p-4">المدفوع</th>
                    <th class="p-4">تاريخ الأرشفة</th>
                </tr>
            </thead>

            <tbody>

                @forelse($visits as $visit)

                <tr class="border-t">

                    <td class="p-4">
                        {{ $visit->patient?->name }}
                    </td>

                    <td class="p-4">
                        {{ $visit->doctor?->name }}
                    </td>

                    <td class="p-4">
                        {{ $visit->total_amount }}
                    </td>

                    <td class="p-4">
                        {{ $visit->paid_cost }}
                    </td>

                    <td class="p-4 text-gray-500">
                        {{ $visit->deleted_at }}
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="5" class="p-10 text-center text-gray-400">
                        لا يوجد زيارات مؤرشفة
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-5">
        {{ $visits->links() }}
    </div>

</div>
