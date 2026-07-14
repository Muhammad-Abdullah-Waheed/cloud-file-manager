<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ShareItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'receiver_email' => ['required', 'email', 'exists:users,email'],
            'permission'     => ['required', 'in:read,write'],
            'shared_type'    => ['required', 'in:file,folder'],
            'shared_id'      => ['required', 'integer'],
        ];
    }
}
