<div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 px-4 py-3 rounded-xl text-sm font-medium"
         style="background: #dcfce7; color: #166534;">
        {{ session('success') }}
    </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">المواعيد</h2>
            <p class="text-sm text-gray-500 mt-1">إدارة مواعيد المركز</p>
        </div>
        <button wire:click="openModal"
                class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition"
                style="background: #511269;"
                onmouseover="this.style.background='#3a0a52'"
                onmouseout="this.style.background='#511269'">
            <span class="text-base">＋</span>
            <span>موعد جديد</span>
        </button>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3 mb-4">
        <div class="relative">
            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
            <input wire:model.live.debounce.300ms="search"
                   type="text" placeholder="ابحثي باسم المريضة..."
                   class="border border-gray-200 rounded-xl pr-9 pl-4 py-2.5 text-sm focus:outline-none focus:ring-2 w-56">
        </div>

        <select wire:model.live="specialty"
                class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2"
                style="min-width: 160px;">
            <option value="">🏥 كل التخصصات</option>
            @foreach($specialties as $sp)
            <option value="{{ $sp->id }}">{{ $sp->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="status"
                class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2"
                style="min-width: 140px;">
            <option value="">كل الحالات</option>
            <option value="scheduled">مجدول</option>
            <option value="completed">مكتمل</option>
            <option value="cancelled">ملغي</option>
        </select>

        @if($specialty)
        <div class="flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium"
             style="background: #f5f0fa; color: #511269;">
            <span>تخصص: {{ $specialties->firstWhere('id', $specialty)?->name }}</span>
            <button wire:click="$set('specialty', '')" class="mr-1 hover:opacity-70">✕</button>
        </div>
        @endif
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-right text-sm">
            <thead style="background: #f5f0fa;">
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
                @forelse($appointments as $appointment)
                <tr class="hover:bg-purple-50/30 transition">
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">{{ $appointment->id }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $appointment->patient_name }}</td>
                    <td class="px-6 py-4">
                        <div class="text-gray-800 font-medium">{{ $appointment->doctor_name }}</div>
                        <div class="text-xs mt-0.5 px-2 py-0.5 rounded-full inline-block"
                             style="background: #ede9fe; color: #6d28d9;">
                            {{ $appointment->specialty_name }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $appointment->service_name }}</td>
                    <td class="px-6 py-4 text-gray-600 text-xs font-mono">
                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}
                        <div class="text-gray-400">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $appointment->status === 'scheduled' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ $appointment->status === 'scheduled' ? '🕐 مجدول' : ($appointment->status === 'completed' ? '✅ مكتمل' : '❌ ملغي') }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button wire:click="openStatusModal({{ $appointment->id }}, '{{ $appointment->status }}')"
                                    class="text-xs px-3 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                تعديل الحالة
                            </button>
                            <button wire:click="deleteAppointment({{ $appointment->id }})"
                                    wire:confirm="تأكيدي حذف هذا الموعد؟"
                                    class="text-xs px-3 py-1.5 rounded-lg border border-red-100 text-red-500 hover:bg-red-50 transition">
                                حذف
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-4xl">📅</span>
                            <p class="font-medium">لا توجد مواعيد
                            @if($specialty || $search || $status) مطابقة للفلاتر المحددة @endif
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $appointments->links() }}</div>
    </div>


    {{-- ══════════════════════════════════════════════════ --}}
    {{-- MODAL — إضافة موعد جديد                          --}}
    {{-- ══════════════════════════════════════════════════ --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center"
         style="background: rgba(0,0,0,0.5);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">

            {{-- Modal Header --}}
            <div class="px-6 py-4 flex items-center justify-between"
                 style="background: #511269;">
                <h3 class="text-white font-bold text-lg">📅 موعد جديد</h3>
                <button wire:click="closeModal" class="text-white opacity-70 hover:opacity-100 text-xl">✕</button>
            </div>

            {{-- Modal Body --}}
            <div class="px-6 py-5 space-y-4 max-h-[70vh] overflow-y-auto">

                {{-- البحث عن مريضة --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">المريضة <span class="text-red-500">*</span></label>

                    @if($selectedPatient)
                    {{-- مريضة مختارة --}}
                    <div class="flex items-center justify-between px-4 py-2.5 rounded-xl border-2"
                         style="border-color: #511269; background: #f5f0fa;">
                        <span class="font-medium text-gray-800">{{ $selectedPatientName }}</span>
                        <button wire:click="$set('selectedPatient', null)"
                                class="text-xs text-gray-400 hover:text-red-500">تغيير</button>
                    </div>
                    @else
                    {{-- بحث --}}
                    <input wire:model.live.debounce.300ms="patientSearch"
                           type="text" placeholder="ابحثي باسم أو رقم المريضة..."
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                    @if($patientResults->count())
                    <div class="mt-1 border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                        @foreach($patientResults as $patient)
                        <button wire:click="$set('selectedPatient', {{ $patient->id }})"
                                class="w-full text-right px-4 py-2.5 text-sm hover:bg-purple-50 transition border-b border-gray-50 last:border-0">
                            <span class="font-medium text-gray-800">{{ $patient->name }}</span>
                            @if($patient->phone)
                            <span class="text-gray-400 text-xs mr-2">{{ $patient->phone }}</span>
                            @endif
                        </button>
                        @endforeach
                    </div>
                    @elseif($patientSearch)
                    <p class="text-xs text-gray-400 mt-1 px-1">لا توجد نتائج</p>
                    @endif
                    @endif
                    @error('selectedPatient') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- الطبيب --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">الطبيب <span class="text-red-500">*</span></label>
                    <select wire:model="modalDoctorId"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                        <option value="">— اختاري الطبيب —</option>
                        @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->name }} — {{ $doctor->specialty_name }}</option>
                        @endforeach
                    </select>
                    @error('modalDoctorId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- الخدمة --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">الخدمة <span class="text-red-500">*</span></label>
                    <select wire:model="modalServiceId"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                        <option value="">— اختاري الخدمة —</option>
                        @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                    @error('modalServiceId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- التاريخ والوقت --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">التاريخ والوقت <span class="text-red-500">*</span></label>
                    <input wire:model="appointmentDate"
                           type="datetime-local"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                    @error('appointmentDate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- النوع --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">نوع الموعد</label>
                    <div class="flex gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input wire:model="appointmentType" type="radio" value="first_visit" class="accent-purple-700">
                            <span class="text-sm text-gray-700">زيارة أولى</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input wire:model="appointmentType" type="radio" value="follow_up" class="accent-purple-700">
                            <span class="text-sm text-gray-700">متابعة</span>
                        </label>
                    </div>
                </div>

                {{-- ملاحظات --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">ملاحظات</label>
                    <textarea wire:model="appointmentNotes"
                              rows="2"
                              placeholder="أي ملاحظات إضافية..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 resize-none"></textarea>
                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                <button wire:click="closeModal"
                        class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition">
                    إلغاء
                </button>
                <button wire:click="saveAppointment"
                        wire:loading.attr="disabled"
                        class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white transition"
                        style="background: #511269;"
                        onmouseover="this.style.background='#3a0a52'"
                        onmouseout="this.style.background='#511269'">
                    <span wire:loading.remove wire:target="saveAppointment">حفظ الموعد</span>
                    <span wire:loading wire:target="saveAppointment">جاري الحفظ...</span>
                </button>
            </div>
        </div>
    </div>
    @endif


    {{-- ══════════════════════════════════════════════════ --}}
    {{-- MODAL — تعديل الحالة                             --}}
    {{-- ══════════════════════════════════════════════════ --}}
    @if($showStatusModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center"
         style="background: rgba(0,0,0,0.5);">
        <div class="bg-white rounded-2xl shadow-2xl w-80 mx-4 overflow-hidden">
            <div class="px-6 py-4" style="background: #511269;">
                <h3 class="text-white font-bold">تعديل حالة الموعد</h3>
            </div>
            <div class="px-6 py-5 space-y-3">
                <label class="block text-sm font-semibold text-gray-700 mb-2">الحالة الجديدة</label>
                <select wire:model="editStatus"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                    <option value="scheduled">🕐 مجدول</option>
                    <option value="completed">✅ مكتمل</option>
                    <option value="cancelled">❌ ملغي</option>
                </select>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                <button wire:click="$set('showStatusModal', false)"
                        class="px-4 py-2 rounded-xl text-sm text-gray-600 border border-gray-200 hover:bg-gray-50">
                    إلغاء
                </button>
                <button wire:click="saveStatus"
                        class="px-5 py-2 rounded-xl text-sm font-semibold text-white"
                        style="background: #511269;">
                    حفظ
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
