<?php

namespace App\Http\Requests\Cadastro\Transportadora;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->canAccess('cadastro.transportadora.update');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nomeFantasia' => 'required|string|max:80',
            'razaoSocial' => 'required|string|max:80',
            'cnpj' => 'required|string|size:14|unique:transportadora,cnpj,' . $this->transportadora->id,
            'situacao_id' => 'required|exists:padrao_tipo,id',
            'observacao' => 'nullable|string',
            'estadosAtendidos' => 'nullable|array',
            'estadosAtendidos.*' => 'string|size:2',
        ];
    }

    /**
     * Get the custom validation messages.
     */
    public function messages(): array
    {
        return [
            'nomeFantasia.required' => __('messages.transportadora.validation.nomeFantasia.required'),
            'nomeFantasia.string' => __('messages.transportadora.validation.nomeFantasia.string'),
            'nomeFantasia.max' => __('messages.transportadora.validation.nomeFantasia.max'),
            'razaoSocial.required' => __('messages.transportadora.validation.razaoSocial.required'),
            'razaoSocial.string' => __('messages.transportadora.validation.razaoSocial.string'),
            'razaoSocial.max' => __('messages.transportadora.validation.razaoSocial.max'),
            'cnpj.required' => __('messages.transportadora.validation.cnpj.required'),
            'cnpj.string' => __('messages.transportadora.validation.cnpj.string'),
            'cnpj.size' => __('messages.transportadora.validation.cnpj.size'),
            'cnpj.unique' => __('messages.transportadora.validation.cnpj.unique'),
            'situacao_id.required' => __('messages.transportadora.validation.situacao_id.required'),
            'situacao_id.exists' => __('messages.transportadora.validation.situacao_id.exists'),
            'observacao.string' => __('messages.transportadora.validation.observacao.string'),
            'estadosAtendidos.array' => __('messages.transportadora.validation.estadosAtendidos.array'),
            'estadosAtendidos.*.string' => __('messages.transportadora.validation.estadosAtendidos.string'),
            'estadosAtendidos.*.size' => __('messages.transportadora.validation.estadosAtendidos.size'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('cnpj')) {
            $this->merge([
                'cnpj' => preg_replace('/[^0-9]/', '', $this->input('cnpj')),
            ]);
        }
    }
}
