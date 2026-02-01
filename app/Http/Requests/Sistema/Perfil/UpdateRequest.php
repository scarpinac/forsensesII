<?php

namespace App\Http\Requests\Sistema\Perfil;

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
            'descricao' => 'required|string|max:80',
            'permissoes' => 'nullable|array',
            'permissoes.*' => 'integer|exists:permissao,id',
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required' => __('messages.access_level.validation.descricao.required'),
            'descricao.string' => __('messages.access_level.validation.descricao.string'),
            'descricao.max' => __('messages.access_level.validation.descricao.max'),
            'permissoes.array' => __('messages.access_level.validation.permissoes.array'),
            'permissoes.*.integer' => __('messages.access_level.validation.permissoes.integer'),
            'permissoes.*.exists' => __('messages.access_level.validation.permissoes.exists'),
        ];
    }
}
