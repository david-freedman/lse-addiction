<?php

namespace App\Applications\Http\Student\Registration\Controllers;

use App\Domains\Student\Actions\VerifyCodeAction;
use App\Domains\Student\Data\VerifyCodeData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class VerifyPhoneController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = VerifyCodeData::validateAndCreate($request->all());

        $student = VerifyCodeAction::execute($data);

        if (!$student) {
            return back()->withErrors(['code' => __('messages.verification.invalid_code')]);
        }

        return redirect()->route('student.verify-email.show')->with('success', __('messages.verification.phone_verified'));
    }
}
