<div class="w-full min-h-screen flex" style="background: #E3E3E3;">

    <!-- Right Side — Branding -->
    <div class="hidden lg:flex w-1/2 flex-col items-center justify-center p-12" 
         style="background: linear-gradient(135deg, #511269 0%, #7B2D8B 50%, #511269 100%);">
        
        <img src="/images/logo5.png" alt="شهاب" class="w-56 object-contain mb-6">
        
        <div class="text-center">
            <h1 class="text-3xl font-bold text-white mb-2">عيادات شهاب التخصصية</h1>
            <div class="flex items-center justify-center gap-3 my-3">
                <div class="h-px w-16" style="background: #B8960C;"></div>
                <div class="w-2 h-2 rotate-45" style="background: #B8960C;"></div>
                <div class="h-px w-16" style="background: #B8960C;"></div>
            </div>
            <p class="text-purple-200 text-sm">رعاية طبية متكاملة .. بخصوصية واهتمام</p>
        </div>

        <!-- Specialties Icons -->
        <div class="flex gap-4 mt-10">
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl"
                 style="background: rgba(184,150,12,0.2); border: 1px solid #B8960C;">💆</div>
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl"
                 style="background: rgba(184,150,12,0.2); border: 1px solid #B8960C;">🦷</div>
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl"
                 style="background: rgba(184,150,12,0.2); border: 1px solid #B8960C;">👶</div>
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl"
                 style="background: rgba(184,150,12,0.2); border: 1px solid #B8960C;">🩺</div>
        </div>
    </div>

    <!-- Left Side — Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-sm">

            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <img src="/images/logo5.png" alt="شهاب" class="h-24 mx-auto object-contain">
                <p class="font-bold mt-2" style="color: #511269;">عيادات شهاب التخصصية</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8">

                <div class="mb-6">
                    <h2 class="text-2xl font-bold" style="color: #511269;">مرحباً بك</h2>
                    <p class="text-sm text-gray-500 mt-1">سجّل دخولك للمتابعة</p>
                </div>

                @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-4 text-sm">
                    {{ session('error') }}
                </div>
                @endif

                <form method="POST" action="/login" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            البريد الإلكتروني
                        </label>
                        <input name="email" type="email" value="{{ old('email') }}"
                               placeholder="admin@gmail.com"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm
                                      focus:outline-none focus:ring-2 focus:border-transparent"
                               style="focus:ring-color: #511269;">
                        @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            كلمة المرور
                        </label>
                        <input name="password" type="password" placeholder="••••••••"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm
                                      focus:outline-none focus:ring-2 focus:border-transparent">
                        @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full text-white py-3 rounded-xl text-sm font-semibold
                                   transition-all duration-200 shadow-md hover:shadow-lg hover:opacity-90"
                            style="background: linear-gradient(to left, #511269, #7B2D8B);">
                        دخول
                    </button>
                </form>

                <p class="text-center text-xs text-gray-400 mt-6">
                    نظام إدارة عيادات شهاب التخصصية
                </p>
            </div>
        </div>
    </div>

</div>