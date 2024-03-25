<?php

namespace Modules\Png\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePngToJpgRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'file-png' => 'required|file|mimetypes:image/png|max:1024',
            'file' => 'required|string',
            'name' => 'required|string',
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
