<div>
    @if(session('success'))
    <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;">✅ {{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">الزيارات</h2>
            <p class="text-sm text-gray-500 mt-1">إدارة زيارات المركز</p>
        </div>
        <button wire:click="openVisitModal"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white shadow hover:opacity-90 transition"
                style="background: linear-gradient(to left, #511269, #7B2D8B);">
            <span class="text-lg leading-none">+</span> زيارة جديدة
        </button>
    </div>

    {{-- Search --}}
    <div class="mb-4">
        <div class="relative max-w-sm">
            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="ابحثي باسم المريضة..."
                   class="w-full border border-gray-200 rounded-xl pr-9 pl-4 py-2.5 text-sm focus:outline-none focus:ring-2">
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-right text-sm">
            <thead style="background:#f5f0fa;">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-600">#</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">المريضة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الطبيب</th>
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
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">{{ $visit->id }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $visit->patient_name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $visit->doctor_name }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">
                                {{ $visit->services_count }} خدمة
                            </span>
                            <button wire:click="viewServices({{ $visit->id }})"
                                    class="text-xs px-2 py-1 rounded-lg hover:opacity-80 transition"
                                    style="background:#ede9fe;color:#511269;">👁</button>
                            <button wire:click="openServiceModal({{ $visit->id }})"
                                    class="text-xs px-2 py-1 rounded-lg hover:opacity-80 transition"
                                    style="background:#f0fdf4;color:#166534;">＋</button>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ number_format($visit->total_amount) }}</td>
                    <td class="px-6 py-4 font-semibold text-green-600">{{ number_format($visit->paid_cost) }}</td>
                    <td class="px-6 py-4">
                        @if($visit->remaining > 0)
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-600">{{ number_format($visit->remaining) }}</span>
                        @else
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-600">مسدد ✅</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs font-mono">{{ \Carbon\Carbon::parse($visit->created_at)->format('Y-m-d') }}</td>
                    <td class="px-6 py-4">
                        <button wire:click="deleteVisit({{ $visit->id }})"
                                wire:confirm="هل أنتِ متأكدة من أرشفة هذه الزيارة؟"
                                class="text-xs px-3 py-1.5 rounded-lg font-medium hover:opacity-80 transition"
                                style="background:#fee2e2;color:#dc2626;">🗑 أرشفة</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-16 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-4xl">🏥</span>
                            <p>لا توجد زيارات حتى الآن</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $visits->links() }}</div>
    </div>

    {{-- Modal زيارة جديدة --}}
    @if($showVisitModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="closeVisitModal">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6" dir="rtl">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-800">🏥 زيارة جديدة</h3>
                <button wire:click="closeVisitModal" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>

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
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">الطبيب <span class="text-red-500">*</span></label>
                <select wire:model="modalDoctorId" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                    <option value="">اختاري الطبيب...</option>
                    @foreach($doctors as $doc)
                    <option value="{{ $doc->id }}">{{ $doc->name }} — {{ $doc->specialty_name }}</option>
                    @endforeach
                </select>
            </div>

            @if($errors->any())
            <div class="mb-4 px-3 py-2 rounded-lg text-xs text-red-600 bg-red-50">{{ $errors->first() }}</div>
            @endif

            <div class="flex gap-3">
                <button wire:click="saveVisit"
                        class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-90 transition"
                        style="background: linear-gradient(to left, #511269, #7B2D8B);">
                    <span wire:loading.remove wire:target="saveVisit">➕ إنشاء الزيارة</span>
                    <span wire:loading wire:target="saveVisit">⏳</span>
                </button>
                <button wire:click="closeVisitModal" class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition">إلغاء</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal إضافة خدمة --}}
    @if($showServiceModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="closeServiceModal">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6" dir="rtl">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-800">➕ إضافة خدمة</h3>
                <button wire:click="closeServiceModal" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">الخدمة <span class="text-red-500">*</span></label>
                <select wire:model.live="modalServiceId" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                    <option value="">اختاري الخدمة...</option>
                    @foreach($services as $svc)
                    <option value="{{ $svc->id }}">{{ $svc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">السعر</label>
                    <input wire:model.live="modalPrice" type="number" min="0" step="0.01"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الحسم</label>
                    <input wire:model="modalDiscount" type="number" min="0" step="0.01"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                </div>
            </div>

            @if($modalPrice > 0)
            <div class="mb-4 px-4 py-3 rounded-xl text-sm" style="background:#f5f0fa;">
                الصافي: <strong>{{ number_format($modalPrice - $modalDiscount) }}</strong> ل.س
            </div>
            @endif

            @if($errors->any())
            <div class="mb-4 px-3 py-2 rounded-lg text-xs text-red-600 bg-red-50">{{ $errors->first() }}</div>
            @endif

            <div class="flex gap-3">
                <button wire:click="saveService"
                        class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-90 transition"
                        style="background: linear-gradient(to left, #166534, #15803d);">
                    <span wire:loading.remove wire:target="saveService">💾 حفظ</span>
                    <span wire:loading wire:target="saveService">⏳</span>
                </button>
                <button wire:click="closeServiceModal" class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition">إلغاء</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal عرض خدمات الزيارة --}}
    @if($showServicesView)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="$set('showServicesView', false)">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 p-6" dir="rtl">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-800">خدمات الزيارة #{{ $viewVisitId }}</h3>
                <button wire:click="$set('showServicesView', false)" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            @if($visitServices->count())
            <table class="w-full text-right text-sm">
                <thead><tr class="border-b border-gray-100">
                    <th class="py-2 px-3 font-semibold text-gray-600">الخدمة</th>
                    <th class="py-2 px-3 font-semibold text-gray-600">السعر</th>
                    <th class="py-2 px-3 font-semibold text-gray-600">الحسم</th>
                    <th class="py-2 px-3 font-semibold text-gray-600">الصافي</th>
                    <th class="py-2 px-3"></th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($visitServices as $vs)
                    <tr>
                        <td class="py-2 px-3 font-medium">{{ $vs->service_name }}</td>
                        <td class="py-2 px-3 text-gray-600">{{ number_format($vs->price) }}</td>
                        <td class="py-2 px-3 text-orange-500">{{ $vs->discount > 0 ? number_format($vs->discount) : '—' }}</td>
                        <td class="py-2 px-3 font-semibold">{{ number_format($vs->net) }}</td>
                        <td class="py-2 px-3">
                            <button wire:click="deleteService({{ $vs->id }})"
                                    wire:confirm="حذف هذه الخدمة؟"
                                    class="text-xs px-2 py-1 rounded hover:opacity-80" style="background:#fee2e2;color:#dc2626;">🗑</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-gray-400 text-center py-8">لا توجد خدمات لهذه الزيارة</p>
            @endif
            <div class="mt-4 flex justify-end">
                <button wire:click="$set('showServicesView', false)" class="px-4 py-2 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50">إغلاق</button>
            </div>
        </div>
    </div>
    @endif
</div>
