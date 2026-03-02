<?php

namespace App\Http\Requests\Cadastro\OrigemProduto;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $origemProdutoId = $this->route('origemProduto')->id;
        
        return [
            'codigo' => 'required|string|max:3|unique:origem_produto,codigo,' . $origemProdutoId . ',id,deleted_at,NULL',
            'descricao' => 'required|string|max:100|unique:origem_produto,descricao,' . $origemProdutoId . ',id,deleted_at,NULL',
            'situacao_id' => 'required|exists:padrao_tipo,id,deleted_at,NULL',
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' => __('messages.product_origin.validation.codigo.required'),
            'codigo.string' => __('messages.product_origin.validation.codigo.string'),
            'codigo.max' => __('messages.product_origin.validation.codigo.max'),
            'codigo.unique' => __('messages.product_origin.validation.codigo.unique'),
            'descricao.required' => __('messages.product_origin.validation.descricao.required'),
            'descricao.string' => __('messages.product_origin.validation.descricao.string'),
            'descricao.max' => __('messages.product_origin.validation.descricao.max'),
            'descricao.unique' => __('messages.product_origin.validation.descricao.unique'),
            'situacao_id.required' => __('messages.product_origin.validation.situacao_id.required'),
            'situacao_id.exists' => __('messages.product_origin.validation.situacao_id.exists'),
        ];
    }
}
