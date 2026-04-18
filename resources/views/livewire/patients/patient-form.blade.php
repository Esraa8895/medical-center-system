<div>
    <div class="space-y-4">
        <!-- Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                اسم المريضة <span class="text-red-500">*</span>
            </label>
            <input wire:model="name" type="text" placeholder="أدخلي الاسم الكامل"
                   class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2
                          {{ $errors->has('name') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}"
                   style="focus-ring-color: #511269;">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Phone -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">رقم الهاتف</label>
            <input wire:model="phone" type="text" placeholder="مثال: 0991234567" dir="ltr"
                   class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2
                          {{ $errors->has('phone') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Age -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">العمر</label>
            <input wire:model="age" type="number" min="1" max="120" placeholder="مثال: 25"
                   class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2
                          {{ $errors->has('age') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
            @error('age') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Previous Diseases -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">الأمراض السابقة</label>
            <textarea wire:model="previous_diseases" rows="3"
                      placeholder="اذكري أي أمراض أو حالات طبية سابقة..."
                      class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 resize-none"></textarea>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 pt-2">
            <button wire:click="save"
                    class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90 shadow"
                    style="background: linear-gradient(to left, #511269, #7B2D8B);">
                <span wire:loading.remove wire:target="save">
                    {{ $patientId ? '💾 حفظ التعديلات' : '➕ إضافة المريضة' }}
                </span>
                <span wire:loading wire:target="save">⏳ جاري الحفظ...</span>
            </button>
            <button wire:click="$dispatch('close-form')"
                    class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition">
                إلغاء
            </button>
        </div>
    </div>
</div>
