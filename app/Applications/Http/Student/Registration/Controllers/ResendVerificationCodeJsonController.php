<?php

namespace App\Applications\Http\Student\Registration\Controllers;

use App\Domains\Student\Actions\SendVerificationCodeAction;
use App\Domains\Student\Exceptions\VerificationRateLimitException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ResendVerificationCodeJsonController
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:email,phone'],
        ]);

        $type = $validated['type'];
        $contactKey = $type === 'email' ? 'registration_email' : 'registration_phone';
        $contact = session($contactKey);

        if (! $contact) {
            return response()->json([
                'success' => false,
                'message' => 'Спочатку відправте код верифікації',
            ], 422);
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

            session([
                $sessionKey => $expiresAt,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Код відправлено повторно',
                'expires_at' => $expiresAt,
            ]);
        } catch (VerificationRateLimitException $e) {
            $nextResendAt = now()->addSeconds($e->secondsUntilResend)->timestamp;

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'next_resend_at' => $nextResendAt,
            ], 429);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Помилка відправки коду',
            ], 500);
        }
    }
}
