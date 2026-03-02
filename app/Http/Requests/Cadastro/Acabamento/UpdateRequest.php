<?php

namespace App\Http\Requests\Cadastro\Acabamento;

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
        $acabamentoId = $this->route('acabamento')->id;
        
        return [
            'descricao' => 'required|string|max:50|unique:acabamento,descricao,' . $acabamentoId . ',id,deleted_at,NULL',
            'tipoAcabamento_id' => 'required|exists:padrao_tipo,id,deleted_at,NULL',
            'cor_id' => 'required|exists:cor,id,deleted_at,NULL',
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required' => __('messages.finish.validation.descricao.required'),
            'descricao.string' => __('messages.finish.validation.descricao.string'),
            'descricao.max' => __('messages.finish.validation.descricao.max'),
            'descricao.unique' => __('messages.finish.validation.descricao.unique'),
            'tipoAcabamento_id.required' => __('messages.finish.validation.tipoAcabamento_id.required'),
            'tipoAcabamento_id.exists' => __('messages.finish.validation.tipoAcabamento_id.exists'),
            'cor_id.required' => __('messages.finish.validation.cor_id.required'),
            'cor_id.exists' => __('messages.finish.validation.cor_id.exists'),
        ];
    }
}
