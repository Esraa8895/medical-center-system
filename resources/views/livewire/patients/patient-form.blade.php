<div>
    @if(session('success'))
    <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;">
        ✅ {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 max-w-lg">
        <h3 class="text-lg font-bold text-gray-800 mb-5">
            {{ $patientId ? '✏️ تعديل بيانات المريضة' : '➕ مريضة جديدة' }}
        </h3>

        {{-- الاسم --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم الكامل <span class="text-red-500">*</span></label>
            <input wire:model="name" type="text" placeholder="اسم المريضة..."
                   class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2
                          @error('name') border-red-300 focus:ring-red-200 @else border-gray-200 @enderror">
            @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- رقم الهاتف --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
            <input wire:model="phone" type="tel" placeholder="09XXXXXXXX"
                   maxlength="13"
                   class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2
                          @error('phone') border-red-300 focus:ring-red-200 @else border-gray-200 @enderror">
            <p class="text-xs text-gray-400 mt-1">مثال: 0912345678 أو +963912345678</p>
            @error('phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- العمر --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">العمر</label>
            <input wire:model="age" type="number" placeholder="العمر..." min="1" max="120"
                   class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2
                          @error('age') border-red-300 focus:ring-red-200 @else border-gray-200 @enderror">
            @error('age')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- الأمراض السابقة --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">الأمراض السابقة</label>
            <textarea wire:model="previous_diseases" rows="3" placeholder="الأمراض السابقة أو الحالة الصحية العامة..."
                      class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 resize-none
                             @error('previous_diseases') border-red-300 @enderror"></textarea>
            @error('previous_diseases')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-3">
            <button wire:click="save"
                    class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90"
                    style="background: linear-gradient(to left, #511269, #7B2D8B);">
                <span wire:loading.remove wire:target="save">💾 {{ $patientId ? 'حفظ التعديلات' : 'إضافة المريضة' }}</span>
                <span wire:loading wire:target="save">⏳ جاري الحفظ...</span>
            </button>
            @if($patientId)
            <button wire:click="$dispatch('cancel-edit')"
                    class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition">
                إلغاء
            </button>
            @endif
        </div>
    </div>
</div>
