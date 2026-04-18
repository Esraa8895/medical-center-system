<?php

namespace App\Modules\Payment\Http\Controllers;

use App\Core\Http\Controllers\Controller;
use App\Core\Traits\ApiResponseTrait;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Resources\PaymentResource;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $payments = Payment::with('patient')->latest()->get();
        return $this->successResponse(PaymentResource::collection($payments));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'visit_id'      => 'required|exists:visits,id',
            'patient_id'    => 'required|exists:patients,id',
            'amount'        => 'required|numeric|min:0',
            // سعر الصرف — يُرسَل فقط لو الريسبشن أدخلت مبلغاً محوَّلاً من عملة أخرى
            'exchange_rate' => 'sometimes|numeric|min:0',
            'notes'         => 'nullable|string|max:500',
        ]);

        $payment = Payment::create($data);
        return $this->successResponse(new PaymentResource($payment), 201);
    }
}
