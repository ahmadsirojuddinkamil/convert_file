<?php

namespace Modules\Monitoring\App\Services;

use Illuminate\Support\Facades\Validator;

class ValidationMonitorService
{
    public function validationLogType($save_type_from_call)
    {
        $validator = Validator::make($save_type_from_call->all(), [
            'type' => 'required|string',
        ]);

        return $validator;
    }

    public function validationLogTime($save_data_from_call)
    {
        $validator = Validator::make($save_data_from_call->all(), [
            'type' => 'required|string',
            'time_start' => 'required|string',
            'time_end' => 'required|string',
        ]);

        return $validator;
    }

    public function validationRegister($save_data_from_call)
    {
        $validator = Validator::make($save_data_from_call->all(), [
            'name' => 'required|string|max:30',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        return $validator;
    }

    public function validationLogin($save_data_from_call)
    {
        $validator = Validator::make($save_data_from_call->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        return $validator;
    }
}
