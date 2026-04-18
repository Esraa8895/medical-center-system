<div>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;">
        ✅ {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium" style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;">
        ❌ {{ session('error') }}
    </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">إدارة الحسابات</h2>
            <p class="text-sm text-gray-500 mt-1">إضافة وتعديل وحذف حسابات المستخدمين</p>
        </div>
        <button wire:click="openForm()"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white shadow hover:opacity-90 transition"
                style="background: linear-gradient(to left, #511269, #7B2D8B);">
            <span class="text-lg leading-none">+</span> إضافة حساب
        </button>
    </div>

    {{-- Search --}}
    <div class="mb-4">
        <div class="relative max-w-sm">
            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
            <input wire:model.live.debounce.300ms="search" type="text"
                   placeholder="ابحث بالاسم أو البريد..."
                   class="w-full border border-gray-200 rounded-xl pr-10 pl-4 py-2.5 text-sm focus:outline-none focus:ring-2">
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-right text-sm">
            <thead style="background:#f5f0fa;">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-600">المستخدم</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">البريد الإلكتروني</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الصلاحية</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-purple-50/30 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                                 style="background: linear-gradient(135deg, #511269, #7B2D8B);">
                                {{ mb_substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                @if($user->id === auth()->id())
                                <span class="text-xs" style="color: #B8960C;">أنت</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600 text-xs font-mono">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        @php $role = $user->getRoleNames()->first(); @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $role === 'admin' ? '' : 'bg-blue-100 text-blue-700' }}"
                              style="{{ $role === 'admin' ? 'background:#f5f0fa; color:#511269;' : '' }}">
                            {{ $role === 'admin' ? '👑 مدير' : '💼 موظف استقبال' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 justify-end flex-wrap">
                            <button wire:click="openForm({{ $user->id }})"
                                    class="px-3 py-1.5 rounded-lg text-xs font-medium hover:opacity-80 transition"
                                    style="background:#ede9fe;color:#511269;">
                                ✏️ تعديل
                            </button>
                            <button wire:click="openPasswordModal({{ $user->id }})"
                                    class="px-3 py-1.5 rounded-lg text-xs font-medium hover:opacity-80 transition"
                                    style="background:#fff7ed;color:#c2410c;">
                                🔑 كلمة المرور
                            </button>
                            @if($user->id !== auth()->id())
                            <button wire:click="deleteUser({{ $user->id }})"
                                    wire:confirm="هل أنت متأكد من حذف هذا الحساب؟"
                                    class="px-3 py-1.5 rounded-lg text-xs font-medium hover:opacity-80 transition"
                                    style="background:#fef2f2;color:#dc2626;">
                                🗑️ حذف
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center text-gray-400">
                        <p class="text-3xl mb-2">👤</p>
                        <p>لا يوجد مستخدمون</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ===== ADD / EDIT USER MODAL ===== --}}
    @if($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center" style="background:rgba(0,0,0,0.45);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">
                    {{ $editId ? '✏️ تعديل الحساب' : '➕ إضافة حساب جديد' }}
                </h3>
                <button wire:click="closeForm()" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">الاسم <span class="text-red-500">*</span></label>
                    <input wire:model="name" type="text" placeholder="اسم المستخدم"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2
                                  {{ $errors->has('name') ? 'border-red-300' : '' }}">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">البريد الإلكتروني <span class="text-red-500">*</span></label>
                    <input wire:model="email" type="email" placeholder="example@email.com" dir="ltr"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2
                                  {{ $errors->has('email') ? 'border-red-300' : '' }}">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @if(!$editId)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">كلمة المرور <span class="text-red-500">*</span></label>
                    <input wire:model="password" type="password" placeholder="8 أحرف على الأقل" dir="ltr"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2
                                  {{ $errors->has('password') ? 'border-red-300' : '' }}">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">الصلاحية <span class="text-red-500">*</span></label>
                    <select wire:model="role"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2">
                        <option value="receptionist">💼 موظف استقبال</option>
                        <option value="admin">👑 مدير</option>
                    </select>
                </div>
                <div class="flex gap-3 pt-2">
                    <button wire:click="saveUser()"
                            class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-90 shadow transition"
                            style="background: linear-gradient(to left, #511269, #7B2D8B);">
                        <span wire:loading.remove wire:target="saveUser">{{ $editId ? '💾 حفظ التعديلات' : '➕ إنشاء الحساب' }}</span>
                        <span wire:loading wire:target="saveUser">⏳ جاري الحفظ...</span>
                    </button>
                    <button wire:click="closeForm()"
                            class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ===== CHANGE PASSWORD MODAL ===== --}}
    @if($showPasswordModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center" style="background:rgba(0,0,0,0.45);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">🔑 تغيير كلمة المرور</h3>
                <button wire:click="closePasswordModal()" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">كلمة المرور الجديدة</label>
                    <input wire:model="newPassword" type="password" placeholder="8 أحرف على الأقل" dir="ltr"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2
                                  {{ $errors->has('newPassword') ? 'border-red-300' : '' }}">
                    @error('newPassword') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">تأكيد كلمة المرور</label>
                    <input wire:model="newPasswordConfirm" type="password" placeholder="أعيدي كتابة كلمة المرور" dir="ltr"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2
                                  {{ $errors->has('newPasswordConfirm') ? 'border-red-300' : '' }}">
                    @error('newPasswordConfirm') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-3 pt-2">
                    <button wire:click="changePassword()"
                            class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-90 shadow transition"
                            style="background: linear-gradient(to left, #c2410c, #ea580c);">
                        <span wire:loading.remove wire:target="changePassword">🔑 تغيير كلمة المرور</span>
                        <span wire:loading wire:target="changePassword">⏳ جاري التغيير...</span>
                    </button>
                    <button wire:click="closePasswordModal()"
                            class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
