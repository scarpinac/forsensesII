<?php

namespace App\Http\Requests\Sistema\Parametro;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'nome' => 'required|string|max:80',
            'descricao' => 'required|string',
            'tipo_id' => 'required|exists:padraotipo,id,deleted_at,NULL,padrao_id,6',
            'valor' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => __('messages.parametro.validation.nome.required'),
            'nome.string' => __('messages.parametro.validation.nome.string'),
            'nome.max' => __('messages.parametro.validation.nome.max'),
            'descricao.required' => __('messages.parametro.validation.descricao.required'),
            'descricao.string' => __('messages.parametro.validation.descricao.string'),
            'tipo_id.required' => __('messages.parametro.validation.tipo_id.required'),
            'tipo_id.exists' => __('messages.parametro.validation.tipo_id.exists'),
            'valor.required' => __('messages.parametro.validation.valor.required'),
            'valor.string' => __('messages.parametro.validation.valor.string'),
        ];
    }
}
