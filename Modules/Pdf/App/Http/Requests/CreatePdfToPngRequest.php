<?php

namespace Modules\Pdf\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePdfToPngRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'file_pdf' => 'required|file|mimetypes:application/pdf|max:1028',
            'link_pdf' => 'required|string',
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
