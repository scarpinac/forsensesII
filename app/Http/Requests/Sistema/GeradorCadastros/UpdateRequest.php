<?php

namespace App\Http\Requests\Sistema\GeradorCadastros;

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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $data = $this->all();

        // Converter checkboxes principais para booleano
        $data['criar_permissoes'] = $this->has('criar_permissoes');
        $data['criar_menu'] = $this->has('criar_menu');
        $data['soft_delete'] = $this->has('soft_delete');

        // Converter checkboxes dos campos dinâmicos para booleano
        if (isset($data['campos']) && is_array($data['campos'])) {
            foreach ($data['campos'] as $key => $campo) {
                $data['campos'][$key]['obrigatorio'] = isset($campo['obrigatorio']);
                $data['campos'][$key]['unique'] = isset($campo['unique']);
            }
        }

        $this->merge($data);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'classe' => 'required|string|max:100',
            'menuPai_id' => 'nullable|exists:padrao_tipo,id',
            'criar_permissoes' => 'nullable|boolean',
            'criar_menu' => 'nullable|boolean',
            'soft_delete' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'classe.required' => __('messages.gerador_cadastro.validation.classe.required'),
            'classe.string' => __('messages.gerador_cadastro.validation.classe.string'),
            'classe.max' => __('messages.gerador_cadastro.validation.classe.max'),
            'menuPai_id.exists' => __('messages.gerador_cadastro.validation.menuPai_id.exists'),
            'criar_permissoes.required' => __('messages.gerador_cadastro.validation.criar_permissoes.required'),
            'criar_permissoes.boolean' => __('messages.gerador_cadastro.validation.criar_permissoes.boolean'),
            'criar_menu.required' => __('messages.gerador_cadastro.validation.criar_menu.required'),
            'criar_menu.boolean' => __('messages.gerador_cadastro.validation.criar_menu.boolean'),
            'soft_delete.required' => __('messages.gerador_cadastro.validation.soft_delete.required'),
            'soft_delete.boolean' => __('messages.gerador_cadastro.validation.soft_delete.boolean'),
        ];
    }
}
