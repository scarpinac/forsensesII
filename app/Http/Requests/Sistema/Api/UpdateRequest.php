<?php

namespace App\Http\Requests\Sistema\Api;

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
            'api_id' => 'required|exists:padrao_tipo,id,deleted_at,NULL,padrao_id,7',
            'credencial' => 'required|string',
            'situacao_id' => 'required|exists:padrao_tipo,id,deleted_at,NULL,padrao_id,1',
        ];
    }

    public function messages(): array
    {
        return [
            'api_id.required' => __('messages.api.validation.api_id.required'),
            'api_id.exists' => __('messages.api.validation.api_id.exists'),
            'credencial.required' => __('messages.api.validation.credencial.required'),
            'credencial.string' => __('messages.api.validation.credencial.string'),
            'situacao_id.required' => __('messages.api.validation.situacao_id.required'),
            'situacao_id.exists' => __('messages.api.validation.situacao_id.exists'),
        ];
    }
}
