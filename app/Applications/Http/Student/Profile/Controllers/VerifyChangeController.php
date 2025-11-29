<?php

namespace App\Applications\Http\Student\Profile\Controllers;

use App\Domains\Student\Actions\UpdateStudentContactAction;
use App\Domains\Student\Data\UpdateContactData;
use App\Domains\Student\Data\VerifyCodeData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class VerifyChangeController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $verifyData = VerifyCodeData::validateAndCreate($request->all());
        $originalStudent = $request->user();

        $updateData = new UpdateContactData(
            email: $verifyData->type === 'email' ? session('pending_email') : null,
            phone: $verifyData->type === 'phone' ? session('pending_phone') : null,
        );

        $updatedStudent = UpdateStudentContactAction::execute(
            student: $originalStudent,
            data: $updateData,
            verifyData: $verifyData
        );

        $contactChanged = ($verifyData->type === 'email' && (string) $updatedStudent->email !== (string) $originalStudent->email)
            || ($verifyData->type === 'phone' && (string) $updatedStudent->phone !== (string) $originalStudent->phone);

        if (!$contactChanged) {
            return back()->withErrors(['code' => 'Невірний код підтвердження або код застарів. Спробуйте ще раз.']);
        }

        session()->forget(['pending_email', 'pending_phone']);

        return redirect()->route('student.profile.show')
            ->with('success', __('messages.profile.contact_updated'));
    }
}
