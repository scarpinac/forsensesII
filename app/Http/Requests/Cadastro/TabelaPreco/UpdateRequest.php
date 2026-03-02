<?php

namespace App\Http\Requests\Cadastro\TabelaPreco;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tabelaPrecoId = $this->route('tabelaPreco')->id;
        
        return [
            'descricao' => 'required|string|max:50|unique:tabela_preco,descricao,' . $tabelaPrecoId . ',id,deleted_at,NULL',
            'vigenciaAte' => 'nullable|date',
            'situacao_id' => 'required|exists:padrao_tipo,id,deleted_at,NULL',
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required' => __('messages.price_table.validation.descricao.required'),
            'descricao.string' => __('messages.price_table.validation.descricao.string'),
            'descricao.max' => __('messages.price_table.validation.descricao.max'),
            'descricao.unique' => __('messages.price_table.validation.descricao.unique'),
            'vigenciaAte.nullable' => __('messages.price_table.validation.vigenciaAte.nullable'),
            'vigenciaAte.date' => __('messages.price_table.validation.vigenciaAte.date'),
            'situacao_id.required' => __('messages.price_table.validation.situacao_id.required'),
            'situacao_id.exists' => __('messages.price_table.validation.situacao_id.exists'),
        ];
    }
}
