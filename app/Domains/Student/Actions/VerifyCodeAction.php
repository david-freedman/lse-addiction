<?php

namespace App\Domains\Student\Actions;

use App\Domains\Student\Data\VerifyCodeData;
use App\Domains\Student\Models\Student;

class VerifyCodeAction
{
    public static function execute(VerifyCodeData $data): ?Student
    {
        $result = \App\Domains\Verification\Actions\VerifyCodeAction::execute($data);

        return $result instanceof Student ? $result : null;
    }

    public static function verifyWithoutStudent(VerifyCodeData $data)
    {
        return \App\Domains\Verification\Actions\VerifyCodeAction::verifyWithoutVerifiable($data);
    }
}
