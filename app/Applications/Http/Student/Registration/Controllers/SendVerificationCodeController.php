<?php

namespace App\Applications\Http\Student\Registration\Controllers;

use App\Domains\Shared\Rules\ValidEmail;
use App\Domains\Shared\Rules\ValidPhone;
use App\Domains\Student\Actions\SendVerificationCodeAction;
use App\Domains\Student\Exceptions\VerificationRateLimitException;
use App\Domains\Student\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class SendVerificationCodeController
{
    public function __invoke(Request $request): JsonResponse
    {
        $type = $request->input('type');

        $rules = [
            'type' => ['required', 'in:email,phone'],
        ];

        if ($type === 'email') {
            $rules['email'] = ['required', 'email', new ValidEmail];
        } else {
            $rules['phone'] = ['required', 'string', new ValidPhone];
        }

        $validated = $request->validate($rules);

        $contact = $type === 'email' ? $validated['email'] : $validated['phone'];

        if ($type === 'email') {
            $existingStudent = Student::where('email', $contact)->first();
            if ($existingStudent) {
                if ($existingStudent->name === null) {
                    $existingStudent->delete();
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => __('validation.custom.email.unique'),
                    ], 422);
                }
            }
        } else {
            $existingStudent = Student::where('phone', $contact)->first();
            if ($existingStudent) {
                if ($existingStudent->name === null) {
                    $existingStudent->delete();
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => __('validation.custom.phone.unique'),
                    ], 422);
                }
            }
        }

        try {
            SendVerificationCodeAction::execute(
                type: $type,
                contact: $contact,
                purpose: 'registration',
                studentId: null
            );

            $expiresAt = now()->addMinutes(15)->timestamp;
            $sessionKey = $type === 'email' ? 'email_code_expires_at' : 'phone_code_expires_at';
            $contactKey = $type === 'email' ? 'registration_email' : 'registration_phone';

            session([
                $sessionKey => $expiresAt,
                $contactKey => $contact,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Код верифікації відправлено',
            ]);
        } catch (VerificationRateLimitException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 429);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Помилка відправки коду',
            ], 500);
        }
    }
}
