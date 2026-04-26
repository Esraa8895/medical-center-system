<div>
    @if(session('success'))
    <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;">✅ {{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">المواعيد</h2>
            <p class="text-sm text-gray-500 mt-1">إدارة مواعيد المركز</p>
        </div>
        <button wire:click="openModal"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white shadow hover:opacity-90 transition"
                style="background: linear-gradient(to left, #511269, #7B2D8B);">
            <span class="text-lg leading-none">+</span> موعد جديد
        </button>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3 mb-4">
        <div class="relative">
            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="ابحثي باسم المريضة..."
                   class="border border-gray-200 rounded-xl pr-9 pl-4 py-2.5 text-sm focus:outline-none focus:ring-2 w-56">
        </div>
        <select wire:model.live="specialty" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="min-width:160px;">
            <option value="">🏥 كل التخصصات</option>
            @foreach($specialties as $sp)
            <option value="{{ $sp->id }}">{{ $sp->name }}</option>
            @endforeach
        </select>
        <select wire:model.live="status" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="min-width:140px;">
            <option value="">كل الحالات</option>
            <option value="scheduled">مجدول</option>
            <option value="completed">مكتمل</option>
            <option value="cancelled">ملغي</option>
        </select>
        @if($specialty)
        <button wire:click="$set('specialty','')" class="px-3 py-1.5 rounded-full text-xs font-medium hover:opacity-70" style="background:#f5f0fa;color:#511269;">
            تخصص: {{ $specialties->firstWhere('id', $specialty)?->name }} ✕
        </button>
        @endif
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-right text-sm">
            <thead style="background:#f5f0fa;">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-600">#</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">المريضة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الطبيب / التخصص</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الخدمة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">التاريخ</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الحالة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($appointments as $appt)
                <tr class="hover:bg-purple-50/30 transition">
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">{{ $appt->id }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $appt->patient_name }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ $appt->doctor_name }}</div>
                        <span class="text-xs px-2 py-0.5 rounded-full" style="background:#ede9fe;color:#6d28d9;">{{ $appt->specialty_name }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $appt->service_name }}</td>
                    <td class="px-6 py-4 text-gray-600 text-xs font-mono">
                        {{ \Carbon\Carbon::parse($appt->appointment_date)->format('Y-m-d') }}
                        <div class="text-gray-400">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $appt->status === 'scheduled' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $appt->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $appt->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ $appt->status === 'scheduled' ? '🕐 مجدول' : ($appt->status === 'completed' ? '✅ مكتمل' : '❌ ملغي') }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 justify-end">
                            <button wire:click="openStatusModal({{ $appt->id }}, '{{ $appt->status }}')"
                                    class="text-xs px-3 py-1.5 rounded-lg font-medium hover:opacity-80 transition"
                                    style="background:#ede9fe;color:#511269;">✏️ الحالة</button>
                            <button wire:click="deleteAppointment({{ $appt->id }})"
                                    wire:confirm="هل أنتِ متأكدة من أرشفة هذا الموعد؟"
                                    class="text-xs px-3 py-1.5 rounded-lg font-medium hover:opacity-80 transition"
                                    style="background:#fee2e2;color:#dc2626;">🗑</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-4xl">📅</span>
                            <p class="font-medium">لا توجد مواعيد @if($specialty || $search || $status) مطابقة للفلاتر @endif</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $appointments->links() }}</div>
    </div>

    {{-- Modal إضافة موعد --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="closeModal">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 p-6 max-h-[90vh] overflow-y-auto" dir="rtl">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-800">📅 موعد جديد</h3>
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
                    <input wire:model.live.debounce.300ms="patientSearch" type="text" placeholder="ابحثي باسم المريضة أو الهاتف..."
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
            </div>

            {{-- طبيب وخدمة --}}
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الطبيب <span class="text-red-500">*</span></label>
                    <select wire:model="modalDoctorId" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2">
                        <option value="">اختاري...</option>
                        @foreach($doctors as $doc)
                        <option value="{{ $doc->id }}">{{ $doc->name }} — {{ $doc->specialty_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الخدمة <span class="text-red-500">*</span></label>
                    <select wire:model="modalServiceId" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2">
                        <option value="">اختاري...</option>
                        @foreach($services as $svc)
                        <option value="{{ $svc->id }}">{{ $svc->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- تاريخ ونوع --}}
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">التاريخ والوقت <span class="text-red-500">*</span></label>
                    <input wire:model="appointmentDate" type="datetime-local"
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">النوع</label>
                    <select wire:model="appointmentType" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2">
                        <option value="first_visit">زيارة أولى</option>
                        <option value="follow_up">متابعة</option>
                    </select>
                </div>
            </div>

            {{-- ملاحظات --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                <textarea wire:model="appointmentNotes" rows="2" placeholder="ملاحظات اختيارية..."
                          class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 resize-none"></textarea>
            </div>

            @if($errors->any())
            <div class="mb-4 px-3 py-2 rounded-lg text-xs text-red-600 bg-red-50">{{ $errors->first() }}</div>
            @endif

            <div class="flex gap-3">
                <button wire:click="saveAppointment"
                        class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90"
                        style="background: linear-gradient(to left, #511269, #7B2D8B);">
                    <span wire:loading.remove wire:target="saveAppointment">💾 حفظ الموعد</span>
                    <span wire:loading wire:target="saveAppointment">⏳ جاري الحفظ...</span>
                </button>
                <button wire:click="closeModal" class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition">إلغاء</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal تعديل الحالة --}}
    @if($showStatusModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="closeStatusModal">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6" dir="rtl">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-800">تعديل حالة الموعد</h3>
                <button wire:click="$set('showStatusModal', false)" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">الحالة الجديدة</label>
                <select wire:model="editStatus" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                    <option value="scheduled">🕐 مجدول</option>
                    <option value="completed">✅ مكتمل</option>
                    <option value="cancelled">❌ ملغي</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button wire:click="saveStatus"
                        class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90"
                        style="background: linear-gradient(to left, #511269, #7B2D8B);">حفظ</button>
                <button wire:click="$set('showStatusModal', false)" class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition">إلغاء</button>
            </div>
        </div>
    </div>
    @endif
</div>
