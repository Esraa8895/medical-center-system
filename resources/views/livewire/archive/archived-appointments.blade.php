<div>

    <h1 class="text-2xl font-bold mb-6">
         المواعيد المؤرشفة
    </h1>

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <table class="w-full text-right">

            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4">المريضة</th>
                    <th class="p-4">الطبيب</th>
                    <th class="p-4">الخدمة</th>
                    <th class="p-4">التاريخ</th>
                    <th class="p-4">الحالة</th>
                    <th class="p-4">تاريخ الأرشفة</th>
                </tr>
            </thead>

            <tbody>

                @forelse($appointments as $appointment)

                <tr class="border-t">

                    <td class="p-4">
                        {{ $appointment->patient?->name }}
                    </td>

                    <td class="p-4">
                        {{ $appointment->doctor?->name }}
                    </td>

                    <td class="p-4">
                        {{ $appointment->service?->name }}
                    </td>

                    <td class="p-4">
                        {{ $appointment->appointment_date }}
                    </td>

                    <td class="p-4">
                        {{ $appointment->status }}
                    </td>

                    <td class="p-4 text-gray-500">
                        {{ $appointment->deleted_at }}
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" class="p-10 text-center text-gray-400">
                        لا يوجد مواعيد مؤرشفة
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-5">
        {{ $appointments->links() }}
    </div>

</div>
