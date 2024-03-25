<?php

namespace App\Services;

class ValidationService
{
    public function validationUuid($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall) || strlen($saveUuidFromCall) != 36) {
            return false;
        }
    }
}
