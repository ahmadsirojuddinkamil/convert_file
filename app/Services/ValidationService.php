<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class ValidationService
{
    public function validationUuid($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall) || strlen($saveUuidFromCall) != 36) {
            return false;
        }
    }

    public function validationPassword($save_password_from_call)
    {
        $validator = Validator::make(
            ['save_password_from_call' => $save_password_from_call],
            ['save_password_from_call' => 'uuid|required|max:36',]
        );

        if ($validator->fails()) {
            return false;
        }
    }
}
