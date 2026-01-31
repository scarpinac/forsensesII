<?php

namespace App\Http\Requests\Sistema\Permissao;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'descricao' => 'required|string|max:80',
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required' => __('messages.permission.validation.descricao.required'),
            'descricao.string' => __('messages.permission.validation.descricao.string'),
            'descricao.max' => __('messages.permission.validation.descricao.max'),
        ];
    }
}
