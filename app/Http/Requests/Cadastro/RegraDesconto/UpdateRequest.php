<?php

namespace App\Http\Requests\Cadastro\RegraDesconto;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $regraDescontoId = $this->route('regraDesconto')->id;
        
        return [
            'descricao' => 'required|string|max:50|unique:regra_desconto,descricao,' . $regraDescontoId . ',id,deleted_at,NULL',
            'periodo' => 'required|integer|min:0',
            'valorBase' => 'required|numeric|min:0',
            'descontoAcrescido' => 'required|numeric|min:0|max:99.99',
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required' => __('messages.discount_rule.validation.descricao.required'),
            'descricao.string' => __('messages.discount_rule.validation.descricao.string'),
            'descricao.max' => __('messages.discount_rule.validation.descricao.max'),
            'descricao.unique' => __('messages.discount_rule.validation.descricao.unique'),
            'periodo.required' => __('messages.discount_rule.validation.periodo.required'),
            'periodo.integer' => __('messages.discount_rule.validation.periodo.integer'),
            'periodo.min' => __('messages.discount_rule.validation.periodo.min'),
            'valorBase.required' => __('messages.discount_rule.validation.valorBase.required'),
            'valorBase.numeric' => __('messages.discount_rule.validation.valorBase.numeric'),
            'valorBase.min' => __('messages.discount_rule.validation.valorBase.min'),
            'descontoAcrescido.required' => __('messages.discount_rule.validation.descontoAcrescido.required'),
            'descontoAcrescido.numeric' => __('messages.discount_rule.validation.descontoAcrescido.numeric'),
            'descontoAcrescido.min' => __('messages.discount_rule.validation.descontoAcrescido.min'),
            'descontoAcrescido.max' => __('messages.discount_rule.validation.descontoAcrescido.max'),
        ];
    }
}
