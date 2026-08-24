<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name'        => 'sometimes|required|string|max:255|unique:categories,name,'.$id,
            'description' => 'nullable|string',
            'image'       => 'nullable|string',
            'is_active'   => 'nullable|boolean',
            'sort_order'  => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم التصنيف مطلوب',
            'name.unique'   => 'اسم التصنيف مضاف مسبقاً، يرجى اختيار اسم آخر',
        ];
    }
}
