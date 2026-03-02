<?php

namespace App\Http\Requests\Cadastro\Tela;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $telaId = $this->route('tela')->id;
        
        return [
            'descricao' => 'required|string|max:50|unique:tela,descricao,' . $telaId . ',id,deleted_at,NULL',
            'cor_id' => 'required|exists:cor,id,deleted_at,NULL',
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required' => __('messages.screen.validation.descricao.required'),
            'descricao.string' => __('messages.screen.validation.descricao.string'),
            'descricao.max' => __('messages.screen.validation.descricao.max'),
            'descricao.unique' => __('messages.screen.validation.descricao.unique'),
            'cor_id.required' => __('messages.screen.validation.cor_id.required'),
            'cor_id.exists' => __('messages.screen.validation.cor_id.exists'),
        ];
    }
}
