<?php

namespace App\Http\Requests\Cadastro\Comissao;

use App\Helpers\Tools;
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
            'valor' => 'required|numeric|min:0|max:99999999.99',
            'tipoComissao_id' => 'required|exists:padrao_tipo,id,deleted_at,NULL',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'valor' => Tools::limparValor($this->valor),
        ]);
    }

    public function messages(): array
    {
        return [
            'valor.required' => __('messages.comissao.validation.valor.required'),
            'valor.numeric' => __('messages.comissao.validation.valor.numeric'),
            'valor.min' => __('messages.comissao.validation.valor.min'),
            'valor.max' => __('messages.comissao.validation.valor.max'),
            'tipoComissao_id.required' => __('messages.comissao.validation.tipoComissao_id.required'),
            'tipoComissao_id.exists' => __('messages.comissao.validation.tipoComissao_id.exists'),
        ];
    }
}
