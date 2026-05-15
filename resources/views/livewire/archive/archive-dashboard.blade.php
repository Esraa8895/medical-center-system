<div>

    <h1 class="text-2xl font-bold mb-6">
        🗂 الأرشيف
    </h1>

    <div class="flex flex-col gap-4 max-w-3xl">

        <!-- Patients -->
        <a href="/archive/patients"
           class="group bg-white border border-gray-100 rounded-2xl px-5 py-4 shadow-sm hover:shadow-lg transition duration-300 flex items-center justify-between">

            <div class="flex items-center gap-4">

                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl"
                     style="background: linear-gradient(to left, #511269, #7B2D8B); color: white;">
                    👩
                </div>

                <div>
                    <h2 class="text-sm font-bold text-gray-800">
                        المرضى المؤرشفين
                    </h2>

                    <p class="text-xs text-gray-500 mt-1">
                        المرضى المحذوفين من النظام
                    </p>
                </div>

            </div>

            <div class="text-left">
                <div class="text-2xl font-extrabold text-purple-700">
                    {{ $patientsCount }}
                </div>

                <div class="text-xs text-gray-400">
                    مريضة
                </div>
            </div>

        </a>

        <!-- Appointments -->
        <a href="/archive/appointments"
           class="group bg-white border border-gray-100 rounded-2xl px-5 py-4 shadow-sm hover:shadow-lg transition duration-300 flex items-center justify-between">

            <div class="flex items-center gap-4">

                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl"
                     style="background: linear-gradient(to left, #2563eb, #3b82f6); color: white;">
                    📅
                </div>

                <div>
                    <h2 class="text-sm font-bold text-gray-800">
                        المواعيد المؤرشفة
                    </h2>

                    <p class="text-xs text-gray-500 mt-1">
                        المواعيد الملغية أو المحذوفة
                    </p>
                </div>

            </div>

            <div class="text-left">
                <div class="text-2xl font-extrabold text-blue-700">
                    {{ $appointmentsCount }}
                </div>

                <div class="text-xs text-gray-400">
                    موعد
                </div>
            </div>

        </a>

        <!-- Visits -->
        <a href="/archive/visits"
           class="group bg-white border border-gray-100 rounded-2xl px-5 py-4 shadow-sm hover:shadow-lg transition duration-300 flex items-center justify-between">

            <div class="flex items-center gap-4">

                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl"
                     style="background: linear-gradient(to left, #059669, #10b981); color: white;">
                    🩺
                </div>

                <div>
                    <h2 class="text-sm font-bold text-gray-800">
                        الزيارات المؤرشفة
                    </h2>

                    <p class="text-xs text-gray-500 mt-1">
                        الزيارات المحذوفة من السجل
                    </p>
                </div>

            </div>

            <div class="text-left">
                <div class="text-2xl font-extrabold text-green-700">
                    {{ $visitsCount }}
                </div>

                <div class="text-xs text-gray-400">
                    زيارة
                </div>
            </div>

        </a>

        <!-- Payments -->
        <a href="/archive/payments"
           class="group bg-white border border-gray-100 rounded-2xl px-5 py-4 shadow-sm hover:shadow-lg transition duration-300 flex items-center justify-between">

            <div class="flex items-center gap-4">

                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl"
                     style="background: linear-gradient(to left, #d97706, #f59e0b); color: white;">
                    💰
                </div>

                <div>
                    <h2 class="text-sm font-bold text-gray-800">
                        المدفوعات المؤرشفة
                    </h2>

                    <p class="text-xs text-gray-500 mt-1">
                        الدفعات المحذوفة من النظام
                    </p>
                </div>

            </div>

            <div class="text-left">
                <div class="text-2xl font-extrabold text-amber-700">
                    {{ $paymentsCount }}
                </div>

                <div class="text-xs text-gray-400">
                    دفعة
                </div>
            </div>

        </a>

    </div>

</div>
