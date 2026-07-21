<?php

namespace Modules\Sharing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShareRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'permission' => ['required', 'in:read,write'],
        ];
    }
}