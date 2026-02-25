<?php

namespace App\Http\Requests\Aviso\Comissao;

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
            'valor' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'valor.required' => __('messages.comissao.validation.valor.required'),
            'valor.string' => __('messages.comissao.validation.valor.string'),
            'valor.integer' => __('messages.comissao.validation.valor.integer'),
            'valor.numeric' => __('messages.comissao.validation.valor.numeric'),
            'valor.regex' => __('messages.comissao.validation.valor.regex'),
            'valor.date' => __('messages.comissao.validation.valor.date'),
            'valor.boolean' => __('messages.comissao.validation.valor.boolean'),
            'valor.max' => __('messages.comissao.validation.valor.max'),
            'valor.unique' => __('messages.comissao.validation.valor.unique'),
            'valor.exists' => __('messages.comissao.validation.valor.exists'),
        ];
    }
}