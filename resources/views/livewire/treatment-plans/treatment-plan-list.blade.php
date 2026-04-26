<div>
    @if(session('success'))
    <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;">✅ {{ session('success') }}</div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">خطط العلاج</h2>
            <p class="text-sm text-gray-500 mt-1">إدارة خطط علاج المريضات</p>
        </div>
        <button wire:click="openModal()"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white shadow hover:opacity-90 transition"
                style="background: linear-gradient(to left, #511269, #7B2D8B);">
            <span class="text-lg leading-none">+</span> خطة علاج جديدة
        </button>
    </div>

    <div class="mb-4">
        <div class="relative max-w-sm">
            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="ابحثي باسم المريضة..."
                   class="w-full border border-gray-200 rounded-xl pr-9 pl-4 py-2.5 text-sm focus:outline-none focus:ring-2">
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-right text-sm">
            <thead style="background:#f5f0fa;">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-600">#</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">المريضة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الطبيب</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الخدمة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الجلسات</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الإجمالي</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الحالة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($plans as $plan)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">{{ $plan->id }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $plan->patient?->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $plan->doctor?->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $plan->service?->name }}</td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $plan->completed_sessions }}/{{ $plan->total_sessions ?? '∞' }}
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ number_format($plan->expected_total) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $plan->status === 'active'    ? 'bg-green-100 text-green-700' : '' }}
                            {{ $plan->status === 'completed' ? 'bg-blue-100  text-blue-700'  : '' }}
                            {{ $plan->status === 'cancelled' ? 'bg-red-100   text-red-700'   : '' }}">
                            {{ $plan->status === 'active' ? '🟢 نشطة' : ($plan->status === 'completed' ? '✅ مكتملة' : '❌ ملغية') }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 justify-end">
                            <button wire:click="openModal({{ $plan->id }})"
                                    class="text-xs px-3 py-1.5 rounded-lg font-medium hover:opacity-80 transition"
                                    style="background:#ede9fe;color:#511269;">✏️ تعديل</button>
                            <button wire:click="delete({{ $plan->id }})"
                                    wire:confirm="هل أنتِ متأكدة من حذف هذه الخطة؟"
                                    class="text-xs px-3 py-1.5 rounded-lg font-medium hover:opacity-80 transition"
                                    style="background:#fee2e2;color:#dc2626;">🗑</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-4xl">📋</span>
                            <p>لا توجد خطط علاج حتى الآن</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $plans->links() }}</div>
    </div>

    {{-- Modal إضافة / تعديل خطة علاج --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="closeModal">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 p-6 max-h-[90vh] overflow-y-auto" dir="rtl">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-800">
                    {{ $editId ? '✏️ تعديل خطة العلاج' : '➕ خطة علاج جديدة' }}
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>

            {{-- بحث مريضة --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">المريضة <span class="text-red-500">*</span></label>
                @if($selectedPatient)
                    <div class="flex items-center justify-between px-4 py-2.5 rounded-xl border text-sm" style="background:#f0fdf4;border-color:#bbf7d0;">
                        <span class="font-medium text-green-700">✅ {{ $selectedPatientName }}</span>
                        <button wire:click="$set('selectedPatient', null)" class="text-gray-400 hover:text-red-500 text-xs">تغيير</button>
                    </div>
                @else
                    <input wire:model.live.debounce.300ms="patientSearch" type="text" placeholder="ابحثي باسم المريضة..."
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                    @if($patientResults->count())
                    <div class="mt-1 border border-gray-200 rounded-xl overflow-hidden shadow-lg">
                        @foreach($patientResults as $p)
                        <button wire:click="$set('selectedPatient', {{ $p->id }})"
                                class="w-full text-right px-4 py-2.5 text-sm hover:bg-purple-50 border-b border-gray-100 last:border-0 transition">
                            <span class="font-medium">{{ $p->name }}</span>
                            <span class="text-gray-400 text-xs mr-2">{{ $p->phone }}</span>
                        </button>
                        @endforeach
                    </div>
                    @endif
                @endif
                @error('selectedPatient') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الطبيب <span class="text-red-500">*</span></label>
                    <select wire:model="doctorId" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2">
                        <option value="">اختاري...</option>
                        @foreach($doctors as $doc)
                        <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                        @endforeach
                    </select>
                    @error('doctorId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الخدمة <span class="text-red-500">*</span></label>
                    <select wire:model="serviceId" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2">
                        <option value="">اختاري...</option>
                        @foreach($services as $svc)
                        <option value="{{ $svc->id }}">{{ $svc->name }}</option>
                        @endforeach
                    </select>
                    @error('serviceId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">عدد الجلسات</label>
                    <input wire:model="totalSessions" type="number" min="1" placeholder="اتركيه فارغاً إن لم يُحدد"
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الإجمالي المتوقع <span class="text-red-500">*</span></label>
                    <input wire:model="expectedTotal" type="number" min="0" step="0.01" placeholder="0"
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2">
                    @error('expectedTotal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الحسم</label>
                    <input wire:model="discount" type="number" min="0" step="0.01"
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                    <select wire:model="status" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2">
                        <option value="active">نشطة</option>
                        <option value="completed">مكتملة</option>
                        <option value="cancelled">ملغية</option>
                    </select>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                <textarea wire:model="notes" rows="2" placeholder="ملاحظات اختيارية..."
                          class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 resize-none"></textarea>
            </div>

            <div class="flex gap-3">
                <button wire:click="save"
                        class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-90 transition"
                        style="background: linear-gradient(to left, #511269, #7B2D8B);">
                    <span wire:loading.remove wire:target="save">💾 {{ $editId ? 'حفظ التعديلات' : 'إضافة الخطة' }}</span>
                    <span wire:loading wire:target="save">⏳ جاري الحفظ...</span>
                </button>
                <button wire:click="closeModal" class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition">إلغاء</button>
            </div>
        </div>
    </div>
    @endif
</div>
