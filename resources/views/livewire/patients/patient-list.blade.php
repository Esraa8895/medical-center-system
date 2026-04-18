<div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">إدارة المرضى</h2>
            <p class="text-sm text-gray-500 mt-1">قائمة جميع المريضات في المركز</p>
        </div>
    </div>

    <!-- Search -->
    <div class="mb-4">
        <input wire:model.live.debounce.300ms="search"
               type="text"
               placeholder="🔍 ابحثي باسم المريضة..."
               class="w-full md:w-96 border border-gray-200 rounded-lg px-4 py-2.5 text-sm
                      focus:outline-none focus:ring-2 focus:ring-purple-300">
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-right text-sm">
            <thead class="border-b border-gray-100" style="background: #f5f0fa;">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-600">#</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الاسم</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الهاتف</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">العمر</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الأمراض السابقة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الملف</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($patients as $patient)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-gray-400">{{ $patient->id }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $patient->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $patient->phone ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $patient->age ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-500 text-xs">{{ Str::limit($patient->previous_diseases ?? '', 30) }}</td>
                    <td class="px-6 py-4">
                        <a href="/patients/{{ $patient->id }}"
                           class="text-xs px-3 py-1.5 rounded-lg text-white font-medium transition"
                           style="background: #511269;">
                            عرض الملف
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-4xl">👥</span>
                            <p>لا يوجد مرضى حتى الآن</p>
                        </div>
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