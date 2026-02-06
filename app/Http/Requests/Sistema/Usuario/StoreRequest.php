<?php

namespace App\Http\Requests\Sistema\Usuario;

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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'nullable|mimes:jpeg,png,jpg,gif,webp|max:2024', // 2MB max
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('messages.usuario.validation.name.required'),
            'name.string' => __('messages.usuario.validation.name.string'),
            'name.max' => __('messages.usuario.validation.name.max'),
            'email.required' => __('messages.usuario.validation.email.required'),
            'email.string' => __('messages.usuario.validation.email.string'),
            'email.email' => __('messages.usuario.validation.email.email'),
            'email.max' => __('messages.usuario.validation.email.max'),
            'email.unique' => __('messages.usuario.validation.email.unique'),
            'password.required' => __('messages.usuario.validation.password.required'),
            'password.string' => __('messages.usuario.validation.password.string'),
            'password.min' => __('messages.usuario.validation.password.min'),
            'password.confirmed' => __('messages.usuario.validation.password.confirmed'),
            'avatar.mimes' => __('messages.usuario.validation.avatar.mimes'),
            'avatar.max' => __('messages.usuario.validation.avatar.max'),
        ];
    }
}
