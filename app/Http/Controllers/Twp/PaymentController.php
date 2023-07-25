<?php

namespace App\Http\Controllers\Twp;

use App\Http\Requests\Twp\PaymentEditRequest;
use App\Http\Requests\Twp\PaymentStoreRequest;
use App\Models\Twp\Payment;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PaymentStoreRequest $request)
    {
        $payment = Payment::create($request->validated());

        return Redirect::route('twp.students.show', [$payment->twp_student_id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PaymentEditRequest $request, Payment $payment)
    {
        Payment::where('id', $payment->id)->update($request->validated());
        $payment = Payment::find($payment->id);

        return Redirect::route('twp.students.show', [$payment->twp_student_id]);
    }
}
