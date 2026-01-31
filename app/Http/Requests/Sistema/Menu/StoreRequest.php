<?php

namespace App\Http\Requests\Sistema\Menu;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'descricao' => 'required|string|max:25',
            'icone' => 'nullable|string|max:50',
            'rota' => 'required|string|max:80',
            'menuPai_id' => 'nullable|exists:menu,id',
            'permissao_id' => 'required|exists:permissao,id',
            'situacao_id' => 'required|exists:padrao_tipo,id',
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required' => __('messages.menu.validation.descricao.required'),
            'descricao.string' => __('messages.menu.validation.descricao.string'),
            'descricao.max' => __('messages.menu.validation.descricao.max'),
            'icone.string' => __('messages.menu.validation.icone.string'),
            'icone.max' => __('messages.menu.validation.icone.max'),
            'rota.required' => __('messages.menu.validation.rota.required'),
            'rota.string' => __('messages.menu.validation.rota.string'),
            'rota.max' => __('messages.menu.validation.rota.max'),
            'menuPai_id.exists' => __('messages.menu.validation.menuPai_id.exists'),
            'permissao_id.required' => __('messages.menu.validation.permissao_id.required'),
            'permissao_id.exists' => __('messages.menu.validation.permissao_id.exists'),
            'situacao_id.required' => __('messages.menu.validation.situacao_id.required'),
            'situacao_id.exists' => __('messages.menu.validation.situacao_id.exists'),
        ];
    }
}
