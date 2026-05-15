<div>
    <!-- Flash Messages -->
    @if(session('success'))
    <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;">
        ✅ {{ session('success') }}
    </div>
    @endif

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">إدارة المرضى</h2>
            <p class="text-sm text-gray-500 mt-1">قائمة جميع المريضات في المركز</p>
        </div>
        <button wire:click="openForm()"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white shadow hover:opacity-90 transition"
                style="background: linear-gradient(to left, #511269, #7B2D8B);">
            <span class="text-lg leading-none">+</span> مريضة جديدة
        </button>
    </div>

    <!-- Search -->
    <div class="mb-4">
        <input wire:model.live.debounce.300ms="search"
               type="text"
               placeholder="🔍 ابحثي باسم المريضة..."
               class="w-full md:w-96 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-300">
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
                    <th class="px-6 py-4 font-semibold text-gray-600">العنوان</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الملاحظات</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($patients as $patient)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-gray-400">{{ $patient->id }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $patient->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $patient->phone ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $patient->age ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-500 text-xs">{{ Str::limit($patient->address ?? '', 30) }}</td>
                    <td class="px-6 py-4 text-gray-500 text-xs">{{ Str::limit($patient->notes ?? '', 30) }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 justify-end flex-wrap">
                            <a href="/patients/{{ $patient->id }}"
                               class="text-xs px-3 py-1.5 rounded-lg text-white font-medium transition"
                               style="background: #511269;">👁 الملف</a>
                            <button wire:click="openForm({{ $patient->id }})"
                                    class="text-xs px-3 py-1.5 rounded-lg font-medium hover:opacity-80 transition"
                                    style="background:#ede9fe;color:#511269;">✏️ تعديل</button>
                            <button wire:click="deletePatient({{ $patient->id }})"
                                    wire:confirm="هل أنتِ متأكدة من حذف هذه المريضة؟"
                                    class="text-xs px-3 py-1.5 rounded-lg font-medium hover:opacity-80 transition"
                                    style="background:#fee2e2;color:#dc2626;">🗑 حذف</button>
                        </div>
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

    <!-- Modal إضافة / تعديل مريضة -->
    @if($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="closeForm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6" dir="rtl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800">
                    {{ $editId ? '✏️ تعديل بيانات المريضة' : '➕ إضافة مريضة جديدة' }}
                </h3>
                <button wire:click="closeForm" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            @livewire('patients.patient-form', ['patientId' => $editId], key('patient-form-' . ($editId ?? 'new')))
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('patient-saved', () => {
            @this.closeForm();
        });
    });
</script>
