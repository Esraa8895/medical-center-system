<div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 px-4 py-3 rounded-xl text-sm font-medium"
         style="background: #dcfce7; color: #166534;">
        {{ session('success') }}
    </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">الزيارات</h2>
            <p class="text-sm text-gray-500 mt-1">إدارة زيارات المركز</p>
        </div>
        <button wire:click="openVisitModal"
                class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition"
                style="background: #1a56a0;"
                onmouseover="this.style.background='#1e3a5f'"
                onmouseout="this.style.background='#1a56a0'">
            <span>＋</span>
            <span>زيارة جديدة</span>
        </button>
    </div>

    {{-- Search --}}
    <div class="mb-4">
        <div class="relative w-72">
            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
            <input wire:model.live.debounce.300ms="search"
                   type="text" placeholder="ابحثي باسم المريضة..."
                   class="w-full border border-gray-200 rounded-xl pr-9 pl-4 py-2.5 text-sm focus:outline-none focus:ring-2">
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-right text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-600">#</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">المريضة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الطبيب / التخصص</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الخدمات</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الكلي</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">المدفوع</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">المتبقي</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">التاريخ</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($visits as $visit)
                <tr class="hover:bg-blue-50/20 transition">
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">{{ $visit->id }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $visit->patient_name }}</td>
                    <td class="px-6 py-4">
                        <div class="text-gray-800 font-medium">{{ $visit->doctor_name }}</div>
                        <div class="text-xs mt-0.5 px-2 py-0.5 rounded-full inline-block"
                             style="background: #dbeafe; color: #1a56a0;">
                            {{ $visit->specialty_name }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <button wire:click="viewServices({{ $visit->id }})"
                                class="text-xs px-3 py-1.5 rounded-lg border transition"
                                style="border-color: #1a56a0; color: #1a56a0;"
                                onmouseover="this.style.background='#dbeafe'"
                                onmouseout="this.style.background='transparent'">
                            {{ $visit->services_count }} خدمة
                            @if($visit->services_count > 0) 👁 @endif
                        </button>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ number_format($visit->total_amount) }}
                    </td>
                    <td class="px-6 py-4 text-green-600 font-medium">
                        {{ number_format($visit->paid_cost) }}
                    </td>
                    <td class="px-6 py-4">
                        @if($visit->remaining > 0)
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-600">
                            {{ number_format($visit->remaining) }}
                        </span>
                        @else
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-600">
                            مسدد ✅
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs font-mono">
                        {{ \Carbon\Carbon::parse($visit->created_at)->format('Y-m-d') }}
                        <div class="text-gray-400">{{ \Carbon\Carbon::parse($visit->created_at)->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button wire:click="openServiceModal({{ $visit->id }})"
                                    class="text-xs px-3 py-1.5 rounded-lg text-white transition"
                                    style="background: #1a56a0;"
                                    onmouseover="this.style.background='#1e3a5f'"
                                    onmouseout="this.style.background='#1a56a0'">
                                ＋ خدمة
                            </button>
                            <button wire:click="deleteVisit({{ $visit->id }})"
                                    wire:confirm="تأكيدي حذف هذه الزيارة وكل خدماتها؟"
                                    class="text-xs px-3 py-1.5 rounded-lg border border-red-100 text-red-500 hover:bg-red-50 transition">
                                حذف
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-16 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-4xl">🏥</span>
                            <p class="font-medium">لا توجد زيارات</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $visits->links() }}</div>
    </div>


    {{-- ══════════════════════════════════════════════════ --}}
    {{-- MODAL — زيارة جديدة                              --}}
    {{-- ══════════════════════════════════════════════════ --}}
    @if($showVisitModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center"
         style="background: rgba(0,0,0,0.5);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">

            <div class="px-6 py-4 flex items-center justify-between"
                 style="background: #1a56a0;">
                <h3 class="text-white font-bold text-lg">🏥 زيارة جديدة</h3>
                <button wire:click="closeVisitModal" class="text-white opacity-70 hover:opacity-100 text-xl">✕</button>
            </div>

            <div class="px-6 py-5 space-y-4">

                {{-- البحث عن مريضة --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        المريضة <span class="text-red-500">*</span>
                    </label>
                    @if($selectedPatient)
                    <div class="flex items-center justify-between px-4 py-2.5 rounded-xl border-2"
                         style="border-color: #1a56a0; background: #dbeafe;">
                        <span class="font-medium text-gray-800">{{ $selectedPatientName }}</span>
                        <button wire:click="$set('selectedPatient', null)"
                                class="text-xs text-gray-400 hover:text-red-500">تغيير</button>
                    </div>
                    @else
                    <input wire:model.live.debounce.300ms="patientSearch"
                           type="text" placeholder="ابحثي باسم أو رقم المريضة..."
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                    @if($patientResults->count())
                    <div class="mt-1 border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                        @foreach($patientResults as $patient)
                        <button wire:click="$set('selectedPatient', {{ $patient->id }})"
                                class="w-full text-right px-4 py-2.5 text-sm hover:bg-blue-50 transition border-b border-gray-50 last:border-0">
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
                    @error('selectedPatient')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- الطبيب --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        الطبيب <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="modalDoctorId"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                        <option value="">— اختاري الطبيب —</option>
                        @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">
                            {{ $doctor->name }} — {{ $doctor->specialty_name }}
                        </option>
                        @endforeach
                    </select>
                    @error('modalDoctorId')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <p class="text-xs text-gray-400">
                    💡 بعد إنشاء الزيارة بتقدري تضيفي الخدمات عليها من زر "＋ خدمة"
                </p>

            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                <button wire:click="closeVisitModal"
                        class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition">
                    إلغاء
                </button>
                <button wire:click="saveVisit"
                        wire:loading.attr="disabled"
                        class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white transition"
                        style="background: #1a56a0;"
                        onmouseover="this.style.background='#1e3a5f'"
                        onmouseout="this.style.background='#1a56a0'">
                    <span wire:loading.remove wire:target="saveVisit">إنشاء الزيارة</span>
                    <span wire:loading wire:target="saveVisit">جاري الحفظ...</span>
                </button>
            </div>

        </div>
    </div>
    @endif


    {{-- ══════════════════════════════════════════════════ --}}
    {{-- MODAL — إضافة خدمة لزيارة                        --}}
    {{-- ══════════════════════════════════════════════════ --}}
    @if($showServiceModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center"
         style="background: rgba(0,0,0,0.5);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden">

            <div class="px-6 py-4 flex items-center justify-between"
                 style="background: #1a56a0;">
                <h3 class="text-white font-bold text-lg">➕ إضافة خدمة</h3>
                <button wire:click="closeServiceModal" class="text-white opacity-70 hover:opacity-100 text-xl">✕</button>
            </div>

            <div class="px-6 py-5 space-y-4">

                {{-- الخدمة --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        الخدمة <span class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="modalServiceId"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                        <option value="">— اختاري الخدمة —</option>
                        @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                    @error('modalServiceId')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- السعر --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        السعر (ل.س) <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="modalPrice"
                           type="number" min="0" step="any"
                           placeholder="0"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                    <p class="text-xs text-gray-400 mt-1">السعر يُحدَّث تلقائياً من سعر الخدمة — بتقدري تعدليه</p>
                    @error('modalPrice')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- الحسم --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        الحسم (ل.س)
                    </label>
                    <input wire:model="modalDiscount"
                           type="number" min="0" step="any"
                           placeholder="0"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                </div>

                {{-- الصافي --}}
                @if($modalPrice > 0)
                <div class="px-4 py-3 rounded-xl" style="background: #f0fdf4;">
                    <span class="text-sm font-semibold text-gray-700">الصافي: </span>
                    <span class="text-green-600 font-bold">
                        {{ number_format($modalPrice - ($modalDiscount ?? 0)) }} ل.س
                    </span>
                </div>
                @endif

            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                <button wire:click="closeServiceModal"
                        class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition">
                    إلغاء
                </button>
                <button wire:click="saveService"
                        wire:loading.attr="disabled"
                        class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white transition"
                        style="background: #1a56a0;"
                        onmouseover="this.style.background='#1e3a5f'"
                        onmouseout="this.style.background='#1a56a0'">
                    <span wire:loading.remove wire:target="saveService">إضافة</span>
                    <span wire:loading wire:target="saveService">جاري الحفظ...</span>
                </button>
            </div>

        </div>
    </div>
    @endif


    {{-- ══════════════════════════════════════════════════ --}}
    {{-- MODAL — عرض خدمات الزيارة                        --}}
    {{-- ══════════════════════════════════════════════════ --}}
    @if($showServicesView)
    <div class="fixed inset-0 z-50 flex items-center justify-center"
         style="background: rgba(0,0,0,0.5);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">

            <div class="px-6 py-4 flex items-center justify-between"
                 style="background: #1a56a0;">
                <div>
                    <h3 class="text-white font-bold text-lg">خدمات الزيارة</h3>
                    @if($viewVisitPatientName)
                    <p class="text-blue-200 text-sm">{{ $viewVisitPatientName }}</p>
                    @endif
                </div>
                <button wire:click="$set('showServicesView', false)"
                        class="text-white opacity-70 hover:opacity-100 text-xl">✕</button>
            </div>

            <div class="px-6 py-4">
                @if($visitServices->count())
                <table class="w-full text-right text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="pb-3 font-semibold text-gray-600">الخدمة</th>
                            <th class="pb-3 font-semibold text-gray-600">السعر</th>
                            <th class="pb-3 font-semibold text-gray-600">الحسم</th>
                            <th class="pb-3 font-semibold text-gray-600">الصافي</th>
                            <th class="pb-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($visitServices as $vs)
                        <tr>
                            <td class="py-3 font-medium text-gray-800">{{ $vs->service_name }}</td>
                            <td class="py-3 text-gray-600">{{ number_format($vs->price) }}</td>
                            <td class="py-3 text-orange-500">
                                {{ $vs->discount > 0 ? number_format($vs->discount) : '—' }}
                            </td>
                            <td class="py-3 font-semibold text-gray-800">{{ number_format($vs->net) }}</td>
                            <td class="py-3">
                                <button wire:click="deleteService({{ $vs->id }})"
                                        wire:confirm="حذف هذه الخدمة؟"
                                        class="text-xs text-red-400 hover:text-red-600">حذف</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-200">
                            <td colspan="3" class="pt-3 font-bold text-gray-700">الإجمالي</td>
                            <td class="pt-3 font-bold text-green-600">
                                {{ number_format($visitServices->sum('net')) }} ل.س
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                @else
                <div class="py-12 text-center text-gray-400">
                    <span class="text-4xl">📋</span>
                    <p class="mt-2">لا توجد خدمات مضافة لهذه الزيارة</p>
                </div>
                @endif
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex justify-between items-center">
                <button wire:click="openServiceModal({{ $viewVisitId }})"
                        class="text-sm px-4 py-2 rounded-xl text-white"
                        style="background: #1a56a0;">
                    ＋ إضافة خدمة
                </button>
                <button wire:click="$set('showServicesView', false)"
                        class="px-5 py-2 rounded-xl text-sm text-gray-600 border border-gray-200 hover:bg-gray-50">
                    إغلاق
                </button>
            </div>

        </div>
    </div>
    @endif

</div>
