<?php

namespace App\Http\Requests;
 
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class FileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    { 
        $response =  Gate::inspect('update', $this->file);

        if ($response->allowed()) {
            return true;
        } else {
            throw new AuthorizationException($response->message());
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [ 
            'category' => 'nullable|integer',
            'name' => 'string|max:255',
        ];
    } 
}