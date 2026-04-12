<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">لوحة التقارير</h2>
        <p class="text-sm text-gray-500 mt-1">نظرة عامة على المركز</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500">إجمالي المرضى</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ $totalPatients }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500">إجمالي الزيارات</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $totalVisits }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500">إجمالي الأطباء</p>
            <p class="text-3xl font-bold text-purple-600 mt-1">{{ $totalDoctors }}</p>
        </div>
    </div>

    <!-- Revenue -->
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 mb-4">الإيرادات حسب العملة</h3>
            @foreach($revenueByCurrency as $rev)
            <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                <span class="text-sm font-medium text-gray-600">{{ $rev->currency }}</span>
                <span class="font-bold text-gray-800">{{ number_format($rev->total) }}</span>
            </div>
            @endforeach
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 mb-4">حصص الأطباء</h3>
            @foreach($doctorShares as $doctor)
            <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                <span class="text-sm font-medium text-gray-600">{{ $doctor->name }}</span>
                <span class="font-bold text-gray-800">{{ number_format($doctor->total_share) }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Visits -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <h3 class="font-semibold text-gray-700 mb-4">آخر الزيارات</h3>
        <table class="w-full text-right text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="pb-3 font-semibold text-gray-600">المريضة</th>
                    <th class="pb-3 font-semibold text-gray-600">الطبيب</th>
                    <th class="pb-3 font-semibold text-gray-600">المبلغ</th>
                    <th class="pb-3 font-semibold text-gray-600">التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentVisits as $visit)
                <tr class="border-b border-gray-50 last:border-0">
                    <td class="py-3 font-medium text-gray-800">{{ $visit->patient_name }}</td>
                    <td class="py-3 text-gray-600">{{ $visit->doctor_name }}</td>
                    <td class="py-3 text-gray-600">{{ number_format($visit->total_amount) }}</td>
                    <td class="py-3 text-gray-600">{{ \Carbon\Carbon::parse($visit->created_at)->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>