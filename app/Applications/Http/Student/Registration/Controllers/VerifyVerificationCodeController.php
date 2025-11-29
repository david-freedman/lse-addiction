<?php

namespace App\Applications\Http\Student\Registration\Controllers;

use App\Domains\Student\Actions\VerifyCodeAction;
use App\Domains\Student\Data\VerifyCodeData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class VerifyVerificationCodeController
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'size:4'],
            'type' => ['required', 'in:email,phone'],
        ]);

        $type = $validated['type'];
        $contactKey = $type === 'email' ? 'registration_email' : 'registration_phone';
        $contact = session($contactKey);

        if (!$contact) {
            return response()->json([
                'success' => false,
                'message' => 'Спочатку відправте код верифікації',
            ], 422);
        }

        $data = VerifyCodeData::validateAndCreate([
            'code' => $validated['code'],
            'type' => $type,
            'contact' => $contact,
        ]);

        $verification = VerifyCodeAction::verifyWithoutStudent($data);

        if (!$verification) {
            return response()->json([
                'success' => false,
                'message' => 'Невірний код верифікації',
            ], 422);
        }

        $verifiedKey = $type === 'email' ? 'email_verified' : 'phone_verified';
        $verifiedContactKey = $type === 'email' ? 'verified_email' : 'verified_phone';

        session([
            $verifiedKey => true,
            $verifiedContactKey => $contact,
        ]);

        return response()->json([
            'success' => true,
            'message' => $type === 'email' ? 'Email верифіковано' : 'Телефон верифіковано',
            'verified' => true,
        ]);
    }
}
