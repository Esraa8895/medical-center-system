<div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 px-4 py-3 rounded-xl text-sm font-medium"
         style="background:#dcfce7; color:#166534;">
        {{ session('success') }}
    </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">تكاليف المركز</h2>
            <p class="text-sm text-gray-500 mt-1">مصاريف المواد والصيانة</p>
        </div>
        <button wire:click="openModal"
                class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition"
                style="background:#9a3412;"
                onmouseover="this.style.background='#7c2d12'"
                onmouseout="this.style.background='#9a3412'">
            <span>＋</span><span>مصروف جديد</span>
        </button>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3 mb-4">
        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-500">من</label>
            <input wire:model.live="dateFrom" type="date"
                   class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2">
        </div>
        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-500">إلى</label>
            <input wire:model.live="dateTo" type="date"
                   class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2">
        </div>
        <select wire:model.live="category"
                class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2">
            <option value="">كل التصنيفات</option>
            <option value="supplies">مواد ومستلزمات</option>
            <option value="maintenance">صيانة</option>
            <option value="other">أخرى</option>
        </select>
    </div>

    {{-- بطاقة الإجمالي --}}
    <div class="mb-6 px-6 py-4 rounded-xl border"
         style="background:#fff7ed; border-color:#fed7aa;">
        <p class="text-sm text-gray-500">إجمالي التكاليف للفترة المحددة</p>
        <p class="text-2xl font-bold mt-1" style="color:#9a3412;">
            {{ number_format($total) }}
            <span class="text-base font-normal text-gray-500">ل.س</span>
        </p>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-right text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-600">الوصف</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">التصنيف</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">المبلغ</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">التاريخ</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">ملاحظات</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($expenses as $expense)
                <tr class="hover:bg-orange-50/20 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $expense->title }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $expense->category === 'supplies'    ? 'bg-blue-100 text-blue-700'   : '' }}
                            {{ $expense->category === 'maintenance' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $expense->category === 'other'       ? 'bg-gray-100 text-gray-600'   : '' }}">
                            {{ $categoryLabels[$expense->category] ?? $expense->category }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold" style="color:#9a3412;">
                        {{ number_format($expense->amount) }}
                        <span class="text-xs font-normal text-gray-400"> ل.س</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs font-mono">
                        {{ $expense->expense_date->format('Y-m-d') }}
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-sm">
                        {{ $expense->notes ?? '—' }}
                    </td>
                    <td class="px-6 py-4">
                        <button wire:click="delete({{ $expense->id }})"
                                wire:confirm="حذف هذا المصروف؟"
                                class="text-xs px-3 py-1.5 rounded-lg border border-red-100 text-red-500 hover:bg-red-50 transition">
                            حذف
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-4xl">💰</span>
                            <p class="font-medium">لا توجد تكاليف مسجلة للفترة المحددة</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>


    {{-- MODAL --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center"
         style="background:rgba(0,0,0,0.5);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">

            <div class="px-6 py-4 flex items-center justify-between"
                 style="background:#9a3412;">
                <h3 class="text-white font-bold text-lg">💰 مصروف جديد</h3>
                <button wire:click="$set('showModal',false)"
                        class="text-white opacity-70 hover:opacity-100 text-xl">✕</button>
            </div>

            <div class="px-6 py-5 space-y-4">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        الوصف <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="title" type="text"
                           placeholder="مثلاً: سيرومات 30 حبة..."
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            المبلغ (ل.س) <span class="text-red-500">*</span>
                        </label>
                        <input wire:model="amount" type="number" min="0" step="any"
                               placeholder="0"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                        @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            التاريخ <span class="text-red-500">*</span>
                        </label>
                        <input wire:model="expenseDate" type="date"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                        @error('expenseDate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">التصنيف</label>
                    <select wire:model="newCategory"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                        <option value="supplies">مواد ومستلزمات</option>
                        <option value="maintenance">صيانة</option>
                        <option value="other">أخرى</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">ملاحظات</label>
                    <input wire:model="notes" type="text"
                           placeholder="أي تفاصيل إضافية..."
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                </div>

            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                <button wire:click="$set('showModal',false)"
                        class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition">
                    إلغاء
                </button>
                <button wire:click="save"
                        wire:loading.attr="disabled"
                        class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white transition"
                        style="background:#9a3412;"
                        onmouseover="this.style.background='#7c2d12'"
                        onmouseout="this.style.background='#9a3412'">
                    <span wire:loading.remove wire:target="save">حفظ</span>
                    <span wire:loading wire:target="save">جاري الحفظ...</span>
                </button>
            </div>

        </div>
    </div>
    @endif

</div>
