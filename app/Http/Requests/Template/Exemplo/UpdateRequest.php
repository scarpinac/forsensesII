<?php

namespace App\Http\Requests\Template\Exemplo;

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
        $exemploId = $this->route('exemplo')->id;

        return [
            'nome' => 'required|string|max:100',
            'descricao' => 'nullable|string|max:500',
            'status' => 'required|string|max:50',
            'categoria_id' => 'nullable|exists:categorias,id',
            'valor' => 'nullable|numeric|min:0',
            'data_inicio' => 'nullable|date',
            'ativo' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O campo Nome é obrigatório',
            'nome.max' => 'O campo Nome deve ter no máximo 100 caracteres',
            'descricao.max' => 'O campo Descrição deve ter no máximo 500 caracteres',
            'status.required' => 'O campo Status é obrigatório',
            'status.max' => 'O campo Status deve ter no máximo 50 caracteres',
            'categoria_id.exists' => 'A Categoria selecionada não existe',
            'valor.numeric' => 'O campo Valor deve ser um número',
            'valor.min' => 'O campo Valor deve ser maior ou igual a 0',
            'data_inicio.date' => 'O campo Data Início deve ser uma data válida',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'ativo' => $this->has('ativo'),
        ]);
    }
}
