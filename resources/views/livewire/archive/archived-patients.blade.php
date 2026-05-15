<div>

    <h1 class="text-2xl font-bold mb-6">
         المرضى المؤرشفين
    </h1>

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <table class="w-full text-right">

            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4">الاسم</th>
                    <th class="p-4">الهاتف</th>
                    <th class="p-4">العمر</th>
                    <th class="p-4">العنوان</th>
                    <th class="p-4">الملاحظات</th>
                    <th class="p-4">تاريخ الأرشفة</th>
                    <th class="p-4">الإجراءات</th>
                </tr>
            </thead>

            <tbody>

                @foreach($patients as $patient)

                <tr class="border-t">

                    <td class="p-4">
                        {{ $patient->name }}
                    </td>

                    <td class="p-4">
                        {{ $patient->phone }}
                    </td>
                    <td class="p-4">
                        {{ $patient->age }}
                    </td>

                    <td class="p-4">
                        {{ Str::limit($patient->address ?? '—', 30) }}
                    </td>
                    <td class="p-4">
                        {{ Str::limit($patient->notes ?? '—', 30) }}
                    </td>
                    <td class="p-4">
                        {{ $patient->deleted_at }}
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <div class="mt-5">
        {{ $patients->links() }}
    </div>

</div>
