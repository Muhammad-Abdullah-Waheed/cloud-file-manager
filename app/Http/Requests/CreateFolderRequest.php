<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CreateFolderRequest extends FormRequest
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
        $parentId = $this->input('parent_id') ?: null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('folders', 'slug')
                    ->where('user_id', auth()->id())
                    ->where(function ($query) use ($parentId) {
                        if ($parentId === null) {
                            $query->whereNull('parent_id');
                        } else {
                            $query->where('parent_id', $parentId);
                        }
                    }),
            ],
            'parent_id' => ['nullable', 'integer', 'exists:folders,id'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    public function prepareForValidation(): void
    {
        // Convert name to slug before the unique check runs
        if ($this->has('name')) {
            $this->merge(['slug_check' => Str::slug($this->input('name'))]);
        }
    }

    public function messages(): array
    {
        return [
            'slug.unique' => __('folder.name_taken'),
        ];
    }
}
