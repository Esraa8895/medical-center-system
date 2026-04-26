<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect('/patients');
    }
    return back()->withErrors(['email' => 'البريد أو كلمة المرور غير صحيحة'])->withInput();
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::middleware('auth')->group(function () {

    // ── متاح للكل (Admin + Receptionist) ─────────────────
    Route::get('/',                \App\Livewire\Patients\PatientList::class);
    Route::get('/patients',        \App\Livewire\Patients\PatientList::class);
    Route::get('/patients/{id}',   \App\Livewire\Patients\PatientProfile::class);
    Route::get('/treatment-plans', \App\Livewire\TreatmentPlans\TreatmentPlanList::class);
    Route::get('/visits',          \App\Livewire\Visits\VisitList::class);
    Route::get('/appointments',    \App\Livewire\Appointments\AppointmentList::class);
    Route::get('/payments',        \App\Livewire\Payments\PaymentList::class);

    // ── Admin فقط ─────────────────────────────────────────
    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        Route::get('/reports',  \App\Livewire\Reports\ReportDashboard::class);
        Route::get('/users',    \App\Livewire\Users\UserList::class);
        Route::get('/daily',    \App\Livewire\Admin\DailyView::class);
        Route::get('/expenses', \App\Livewire\Admin\ExpenseList::class);
    });
});
