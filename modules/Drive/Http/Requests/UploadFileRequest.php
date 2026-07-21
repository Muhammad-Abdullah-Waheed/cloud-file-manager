<?php

namespace Modules\Drive\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $extensions = env('ALLOWED_FILE_EXTENSIONS', 'jpg,jpeg,png,pdf,doc,docx,zip,txt');

        return [
            'file' => ['required', 'file', 'mimes:'.$extensions, 'max:102400'], // 100MB max
            'parent_id' => ['nullable', 'integer', 'exists:folders,id'],
        ];
    }
}
