<?php

namespace Modules\Drive\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RenameFolderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $folder = $this->route('folder');
        $parentId = $folder->parent_id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                Rule::unique('folders', 'slug')
                    ->ignore($folder->id)
                    ->where('user_id', auth()->id())
                    ->where(function ($query) use ($parentId) {
                        if ($parentId === null) {
                            $query->whereNull('parent_id');
                        } else {
                            $query->where('parent_id', $parentId);
                        }
                    }),
            ],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->input('name', '')),
        ]);
    }

    public function messages(): array
    {
        return [
            'slug.unique' => __('drive::folder.name_taken'),
        ];
    }
}
