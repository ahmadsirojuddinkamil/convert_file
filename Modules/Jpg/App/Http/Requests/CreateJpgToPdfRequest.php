<?php

namespace Modules\Jpg\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateJpgToPdfRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'file' => 'required|file|mimetypes:image/jpeg|max:1024',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
