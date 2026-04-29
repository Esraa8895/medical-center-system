<div>
    @if(session('success'))
    <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium" style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;">❌ {{ session('error') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">الأطباء</h2>
            <p class="text-sm text-gray-500 mt-1">إدارة بيانات الأطباء والتخصصات</p>
        </div>
        @if(auth()->user()?->hasRole('admin'))
        <button wire:click="openCreate"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white shadow hover:opacity-90 transition"
                style="background: linear-gradient(to left, #511269, #7B2D8B);">
            <span class="text-lg leading-none">+</span> إضافة طبيب
        </button>
        @endif
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3 mb-4">
        <div class="relative">
            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="ابحث باسم الطبيب..."
                   class="border border-gray-200 rounded-xl pr-9 pl-4 py-2.5 text-sm focus:outline-none focus:ring-2 w-56">
        </div>
        <select wire:model.live="specialty" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2" style="min-width:160px;">
            <option value="">🏥 كل التخصصات</option>
            @foreach($specialties as $sp)
            <option value="{{ $sp->id }}">{{ $sp->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-right text-sm">
            <thead style="background:#f5f0fa;">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-600">#</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الاسم</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">التخصص</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">نسبة الطبيب</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">نسبة المركز</th>
                    @if(auth()->user()?->hasRole('admin'))
                    <th class="px-6 py-4 font-semibold text-gray-600">إجراءات</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($doctors as $doctor)
                <tr class="hover:bg-purple-50/30 transition">
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">{{ $doctor->id }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ $doctor->name }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-2 py-1 rounded-full" style="background:#ede9fe;color:#6d28d9;">
                            {{ $doctor->specialty?->name ?? '—' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-semibold" style="color:#511269;">
                            {{ $doctor->default_percentage }}%
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-semibold text-green-700">
                            {{ 100 - $doctor->default_percentage }}%
                        </span>
                    </td>
                    @if(auth()->user()?->hasRole('admin'))
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 justify-end">
                            <button wire:click="openEdit({{ $doctor->id }})"
                                    class="text-xs px-3 py-1.5 rounded-lg font-medium hover:opacity-80 transition"
                                    style="background:#ede9fe;color:#511269;">✏️ تعديل</button>
                            <button wire:click="confirmDelete({{ $doctor->id }}, '{{ $doctor->name }}')"
                                    class="text-xs px-3 py-1.5 rounded-lg font-medium hover:opacity-80 transition"
                                    style="background:#fee2e2;color:#dc2626;">🗑 حذف</button>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-4xl">👨‍⚕️</span>
                            <p class="font-medium">لا يوجد أطباء @if($specialty || $search) مطابقون للبحث @endif</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $doctors->links() }}</div>
    </div>

    {{-- Modal إضافة/تعديل --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="closeModal">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6" dir="rtl">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-800">
                    {{ $editId ? '✏️ تعديل بيانات الطبيب' : '👨‍⚕️ إضافة طبيب جديد' }}
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>

            {{-- الاسم --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">اسم الطبيب <span class="text-red-500">*</span></label>
                <input wire:model="name" type="text" placeholder="د. الاسم الكامل..."
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2
                              @error('name') border-red-300 @else border-gray-200 @enderror">
                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- التخصص --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">التخصص <span class="text-red-500">*</span></label>
                <select wire:model="specialtyId" class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2
                               @error('specialtyId') border-red-300 @else border-gray-200 @enderror">
                    <option value="">اختر التخصص...</option>
                    @foreach($specialties as $sp)
                    <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                    @endforeach
                </select>
                @error('specialtyId')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- النسبة --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">نسبة الطبيب (%) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input wire:model.live="defaultPct" type="number" placeholder="مثال: 70" min="0" max="100" step="0.5"
                           class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2
                                  @error('defaultPct') border-red-300 @else border-gray-200 @enderror">
                </div>
                @if($defaultPct !== '' && is_numeric($defaultPct))
                <div class="mt-2 flex gap-3 text-xs">
                    <span class="px-2 py-1 rounded-full" style="background:#ede9fe;color:#511269;">الطبيب: {{ $defaultPct }}%</span>
                    <span class="px-2 py-1 rounded-full bg-green-100 text-green-700">المركز: {{ 100 - $defaultPct }}%</span>
                </div>
                @endif
                @error('defaultPct')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            @if($errors->any())
            <div class="mb-4 px-3 py-2 rounded-lg text-xs text-red-600 bg-red-50">{{ $errors->first() }}</div>
            @endif

            <div class="flex gap-3">
                <button wire:click="save"
                        class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90"
                        style="background: linear-gradient(to left, #511269, #7B2D8B);">
                    <span wire:loading.remove wire:target="save">💾 {{ $editId ? 'حفظ التعديلات' : 'إضافة الطبيب' }}</span>
                    <span wire:loading wire:target="save">⏳ جاري الحفظ...</span>
                </button>
                <button wire:click="closeModal" class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition">إلغاء</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal تأكيد الحذف --}}
    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6" dir="rtl">
            <div class="text-center mb-5">
                <div class="text-5xl mb-3">⚠️</div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">تأكيد الحذف</h3>
                <p class="text-sm text-gray-600">هل أنت متأكد من أرشفة الطبيب <strong>{{ $deleteName }}</strong>؟</p>
                <p class="text-xs text-gray-400 mt-1">لا يمكن حذف الطبيب إذا كان مرتبطاً بزيارات مسجّلة</p>
            </div>
            <div class="flex gap-3">
                <button wire:click="deleteDoctor"
                        class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90"
                        style="background:linear-gradient(to left,#dc2626,#ef4444);">
                    <span wire:loading.remove wire:target="deleteDoctor">🗑 نعم، أرشفة</span>
                    <span wire:loading wire:target="deleteDoctor">⏳ جاري...</span>
                </button>
                <button wire:click="$set('showDeleteModal', false)"
                        class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition">إلغاء</button>
            </div>
        </div>
    </div>
    @endif
</div>
