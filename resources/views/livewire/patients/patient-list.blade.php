<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">إدارة المرضى</h2>
            <p class="text-sm text-gray-500 mt-1">قائمة جميع المريضات في المركز</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-right text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-600">#</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الاسم</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الهاتف</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">العمر</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($patients as $patient)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-gray-400">{{ $patient->id }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $patient->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $patient->phone ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $patient->age ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center text-gray-400">
                        لا يوجد مرضى حتى الآن
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $patients->links() }}
        </div>
    </div>
</div>