<?php

namespace Modules\Png\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePngToPdfRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'file' => 'required|file|mimetypes:image/png|max:1024',
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
