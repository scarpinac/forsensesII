<?php

namespace App\Http\Requests\Cadastro\Cor;

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
        $corId = $this->route('cor')->id;
        
        return [
            'descricao' => 'required|string|max:50|unique:cor,descricao,' . $corId . ',id,deleted_at,NULL',
            'corHexadecimal' => 'required|string|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required' => __('messages.cor.validation.descricao.required'),
            'descricao.string' => __('messages.cor.validation.descricao.string'),
            'descricao.max' => __('messages.cor.validation.descricao.max'),
            'descricao.unique' => __('messages.cor.validation.descricao.unique'),
            'corHexadecimal.required' => __('messages.cor.validation.corHexadecimal.required'),
            'corHexadecimal.string' => __('messages.cor.validation.corHexadecimal.string'),
            'corHexadecimal.max' => __('messages.cor.validation.corHexadecimal.max'),
        ];
    }
}
