<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->input('parent_id')) {
            $category = Category::find($this->input('parent_id'));

            if (!$category) {
                return false;
            }

            return $this->user()->can('create', $category);
        }
        return $this->user()->can('create', Category::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'parent_id' => 'nullable|integer|exists:categories,id,user_id,' . $this->user()->id,
        ];
    }
}
