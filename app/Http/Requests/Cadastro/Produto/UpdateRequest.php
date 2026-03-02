<?php

namespace App\Http\Requests\Cadastro\Produto;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $produtoId = $this->route('produto')->id;
        
        return [
            'descricao' => 'required|string|max:100|unique:produto,descricao,' . $produtoId . ',id,deleted_at,NULL',
            'codigo' => 'required|string|max:50|unique:produto,codigo,' . $produtoId . ',id,deleted_at,NULL',
            'quantidadeVolumes' => 'required|integer|min:0',
            'peso' => 'required|numeric|min:0',
            'altura' => 'required|numeric|min:0',
            'largura' => 'required|numeric|min:0',
            'comprimento' => 'required|numeric|min:0',
            'precoUnitario' => 'required|numeric|min:0',
            'descontoProduto' => 'required|numeric|min:0|max:99.99',
            'familia_id' => 'required|exists:familia,id,deleted_at,NULL',
            'acabamento_id' => 'required|exists:acabamento,id,deleted_at,NULL',
            'tela_id' => 'required|exists:tela,id,deleted_at,NULL',
            'produtoBase' => 'required|boolean',
            'produtoBase_id' => 'nullable|exists:produto,id,deleted_at,NULL',
            'especificacao' => 'nullable|string',
            'observacao' => 'nullable|string',
            'permitirVenda' => 'required|boolean',
            'permitirTela' => 'required|boolean',
            'codigoBarras' => 'nullable|string|max:50',
            'ncm' => 'nullable|integer|min:0',
            'cst' => 'nullable|integer|min:0',
            'cest' => 'nullable|integer|min:0',
            'origem_id' => 'nullable|exists:padrao_tipo,id,deleted_at,NULL',
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required' => __('messages.product.validation.descricao.required'),
            'descricao.string' => __('messages.product.validation.descricao.string'),
            'descricao.max' => __('messages.product.validation.descricao.max'),
            'descricao.unique' => __('messages.product.validation.descricao.unique'),
            'codigo.required' => __('messages.product.validation.codigo.required'),
            'codigo.string' => __('messages.product.validation.codigo.string'),
            'codigo.max' => __('messages.product.validation.codigo.max'),
            'codigo.unique' => __('messages.product.validation.codigo.unique'),
            'quantidadeVolumes.required' => __('messages.product.validation.quantidadeVolumes.required'),
            'quantidadeVolumes.integer' => __('messages.product.validation.quantidadeVolumes.integer'),
            'quantidadeVolumes.min' => __('messages.product.validation.quantidadeVolumes.min'),
            'peso.required' => __('messages.product.validation.peso.required'),
            'peso.numeric' => __('messages.product.validation.peso.numeric'),
            'peso.min' => __('messages.product.validation.peso.min'),
            'altura.required' => __('messages.product.validation.altura.required'),
            'altura.numeric' => __('messages.product.validation.altura.numeric'),
            'altura.min' => __('messages.product.validation.altura.min'),
            'largura.required' => __('messages.product.validation.largura.required'),
            'largura.numeric' => __('messages.product.validation.largura.numeric'),
            'largura.min' => __('messages.product.validation.largura.min'),
            'comprimento.required' => __('messages.product.validation.comprimento.required'),
            'comprimento.numeric' => __('messages.product.validation.comprimento.numeric'),
            'comprimento.min' => __('messages.product.validation.comprimento.min'),
            'precoUnitario.required' => __('messages.product.validation.precoUnitario.required'),
            'precoUnitario.numeric' => __('messages.product.validation.precoUnitario.numeric'),
            'precoUnitario.min' => __('messages.product.validation.precoUnitario.min'),
            'descontoProduto.required' => __('messages.product.validation.descontoProduto.required'),
            'descontoProduto.numeric' => __('messages.product.validation.descontoProduto.numeric'),
            'descontoProduto.min' => __('messages.product.validation.descontoProduto.min'),
            'descontoProduto.max' => __('messages.product.validation.descontoProduto.max'),
            'familia_id.required' => __('messages.product.validation.familia_id.required'),
            'familia_id.exists' => __('messages.product.validation.familia_id.exists'),
            'acabamento_id.required' => __('messages.product.validation.acabamento_id.required'),
            'acabamento_id.exists' => __('messages.product.validation.acabamento_id.exists'),
            'tela_id.required' => __('messages.product.validation.tela_id.required'),
            'tela_id.exists' => __('messages.product.validation.tela_id.exists'),
            'produtoBase.required' => __('messages.product.validation.produtoBase.required'),
            'produtoBase.boolean' => __('messages.product.validation.produtoBase.boolean'),
            'produtoBase_id.nullable' => __('messages.product.validation.produtoBase_id.nullable'),
            'produtoBase_id.exists' => __('messages.product.validation.produtoBase_id.exists'),
            'especificacao.string' => __('messages.product.validation.especificacao.string'),
            'observacao.string' => __('messages.product.validation.observacao.string'),
            'permitirVenda.required' => __('messages.product.validation.permitirVenda.required'),
            'permitirVenda.boolean' => __('messages.product.validation.permitirVenda.boolean'),
            'permitirTela.required' => __('messages.product.validation.permitirTela.required'),
            'permitirTela.boolean' => __('messages.product.validation.permitirTela.boolean'),
            'codigoBarras.string' => __('messages.product.validation.codigoBarras.string'),
            'codigoBarras.max' => __('messages.product.validation.codigoBarras.max'),
            'ncm.integer' => __('messages.product.validation.ncm.integer'),
            'ncm.min' => __('messages.product.validation.ncm.min'),
            'cst.integer' => __('messages.product.validation.cst.integer'),
            'cst.min' => __('messages.product.validation.cst.min'),
            'cest.integer' => __('messages.product.validation.cest.integer'),
            'cest.min' => __('messages.product.validation.cest.min'),
            'origem_id.nullable' => __('messages.product.validation.origem_id.nullable'),
            'origem_id.exists' => __('messages.product.validation.origem_id.exists'),
        ];
    }
}
