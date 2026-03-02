<?php

namespace App\Http\Requests\Cadastro\CondicaoPagamento;

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
        $condicaoPagamentoId = $this->route('condicaoPagamento')->id;
        
        return [
            'descricao' => 'required|string|max:50|unique:condicao_pagamento,descricao,' . $condicaoPagamentoId . ',id,deleted_at,NULL',
            'revenda_id' => 'nullable|exists:revenda,id,deleted_at,NULL',
            'diasEntreParcelas' => 'required|integer|min:0',
            'quantidadeParcelas' => 'required|integer|min:1',
            'situacao_id' => 'required|exists:padrao_tipo,id,deleted_at,NULL',
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required' => __('messages.payment_condition.validation.descricao.required'),
            'descricao.string' => __('messages.payment_condition.validation.descricao.string'),
            'descricao.max' => __('messages.payment_condition.validation.descricao.max'),
            'descricao.unique' => __('messages.payment_condition.validation.descricao.unique'),
            'revenda_id.nullable' => __('messages.payment_condition.validation.revenda_id.nullable'),
            'revenda_id.exists' => __('messages.payment_condition.validation.revenda_id.exists'),
            'diasEntreParcelas.required' => __('messages.payment_condition.validation.diasEntreParcelas.required'),
            'diasEntreParcelas.integer' => __('messages.payment_condition.validation.diasEntreParcelas.integer'),
            'diasEntreParcelas.min' => __('messages.payment_condition.validation.diasEntreParcelas.min'),
            'quantidadeParcelas.required' => __('messages.payment_condition.validation.quantidadeParcelas.required'),
            'quantidadeParcelas.integer' => __('messages.payment_condition.validation.quantidadeParcelas.integer'),
            'quantidadeParcelas.min' => __('messages.payment_condition.validation.quantidadeParcelas.min'),
            'situacao_id.required' => __('messages.payment_condition.validation.situacao_id.required'),
            'situacao_id.exists' => __('messages.payment_condition.validation.situacao_id.exists'),
        ];
    }
}
