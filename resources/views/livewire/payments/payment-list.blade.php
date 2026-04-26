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
            <h2 class="text-2xl font-bold text-gray-800">المدفوعات</h2>
            <p class="text-sm text-gray-500 mt-1">سجل دفعات المركز</p>
        </div>
        <button wire:click="openModal"
                class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition"
                style="background: #166534;"
                onmouseover="this.style.background='#14532d'"
                onmouseout="this.style.background='#166534'">
            <span>＋</span>
            <span>تسجيل دفعة</span>
        </button>
    </div>

    {{-- بطاقة الإجمالي --}}
    <div class="mb-6 px-6 py-4 rounded-xl border"
         style="background: #f0fdf4; border-color: #bbf7d0;">
        <p class="text-sm text-gray-500">إجمالي المدفوعات</p>
        <p class="text-2xl font-bold mt-1" style="color: #166534;">
            {{ number_format($totalPaid) }} <span class="text-base font-normal text-gray-500">ل.س</span>
        </p>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3 mb-4">
        <div class="relative">
            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
            <input wire:model.live.debounce.300ms="search"
                   type="text" placeholder="ابحثي باسم المريضة..."
                   class="border border-gray-200 rounded-xl pr-9 pl-4 py-2.5 text-sm focus:outline-none focus:ring-2 w-56">
        </div>
        <input wire:model.live="dateFrom" type="date"
               class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
        <input wire:model.live="dateTo" type="date"
               class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
        @if($dateFrom || $dateTo)
        <button wire:click="$set('dateFrom',''); $set('dateTo','')"
                class="px-3 py-2 rounded-xl text-xs text-gray-500 border border-gray-200 hover:bg-gray-50">
            مسح الفلتر ✕
        </button>
        @endif
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-right text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-600">#</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">المريضة</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">المبلغ المدفوع</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">ملاحظات</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">التاريخ والوقت</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($payments as $payment)
                <tr class="hover:bg-green-50/20 transition">
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">{{ $payment->id }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $payment->patient_name }}</td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-green-600 text-base">
                            {{ number_format($payment->amount) }}
                        </span>
                        <span class="text-gray-400 text-xs"> ل.س</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-sm">
                        {{ $payment->notes ?? '—' }}
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs font-mono">
                        {{ \Carbon\Carbon::parse($payment->created_at)->format('Y-m-d') }}
                        <div class="text-gray-400">{{ \Carbon\Carbon::parse($payment->created_at)->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <button wire:click="deletePayment({{ $payment->id }})"
                                wire:confirm="أرشفة هذه الدفعة؟ سيتم تعديل المتبقي على الزيارة."
                                class="text-xs px-3 py-1.5 rounded-lg border border-red-100 text-red-500 hover:bg-red-50 transition">
                            أرشفة
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-4xl">💳</span>
                            <p class="font-medium">لا توجد مدفوعات مسجلة</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $payments->links() }}</div>
    </div>


    {{-- ══════════════════════════════════════════════════ --}}
    {{-- MODAL — تسجيل دفعة                               --}}
    {{-- ══════════════════════════════════════════════════ --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center"
         style="background: rgba(0,0,0,0.5);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">

            <div class="px-6 py-4 flex items-center justify-between"
                 style="background: #166534;">
                <h3 class="text-white font-bold text-lg">💳 تسجيل دفعة</h3>
                <button wire:click="closeModal" class="text-white opacity-70 hover:opacity-100 text-xl">✕</button>
            </div>

            <div class="px-6 py-5 space-y-4">

                {{-- اختيار الزيارة --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        الزيارة <span class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="visitId"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                        <option value="">— اختاري الزيارة —</option>
                        @foreach($openVisits as $v)
                        <option value="{{ $v->id }}">
                            {{ $v->patient_name }} — {{ $v->doctor_name }}
                            (متبقي: {{ number_format($v->remaining) }} ل.س)
                        </option>
                        @endforeach
                    </select>
                    @if($openVisits->isEmpty())
                    <p class="text-xs text-gray-400 mt-1">لا توجد زيارات بمتبقي غير مسدد</p>
                    @endif
                    @error('visitId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- المتبقي على الزيارة --}}
                @if($visitId && $visitRemaining > 0)
                <div class="px-4 py-3 rounded-xl flex items-center justify-between"
                     style="background: #fef3c7;">
                    <span class="text-sm text-gray-600">المتبقي على الزيارة:</span>
                    <span class="font-bold" style="color: #92400e;">
                        {{ number_format($visitRemaining) }} ل.س
                    </span>
                </div>
                @endif

                {{-- المبلغ --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        المبلغ المدفوع (ل.س) <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="amount"
                           type="number" min="0" step="any"
                           placeholder="0"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                    @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- ملاحظات --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">ملاحظات</label>
                    <input wire:model="notes"
                           type="text" placeholder="مثلاً: دفعة أولى..."
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                </div>

            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                <button wire:click="closeModal"
                        class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition">
                    إلغاء
                </button>
                <button wire:click="savePayment"
                        wire:loading.attr="disabled"
                        class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white transition"
                        style="background: #166534;"
                        onmouseover="this.style.background='#14532d'"
                        onmouseout="this.style.background='#166534'">
                    <span wire:loading.remove wire:target="savePayment">تسجيل الدفعة</span>
                    <span wire:loading wire:target="savePayment">جاري الحفظ...</span>
                </button>
            </div>

        </div>
    </div>
    @endif

</div>
