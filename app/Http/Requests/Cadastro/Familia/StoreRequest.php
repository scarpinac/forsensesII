<?php

namespace App\Http\Requests\Cadastro\Familia;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'descricao' => 'required|string|max:50|unique:familia,descricao,NULL,id,deleted_at,NULL',
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required' => __('messages.family.validation.descricao.required'),
            'descricao.string' => __('messages.family.validation.descricao.string'),
            'descricao.max' => __('messages.family.validation.descricao.max'),
            'descricao.unique' => __('messages.family.validation.descricao.unique'),
        ];
    }
}
