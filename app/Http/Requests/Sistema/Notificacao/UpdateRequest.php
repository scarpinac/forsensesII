<?php

namespace App\Http\Requests\Sistema\Notificacao;

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
        $notificacaoId = $this->route('notificacao')->id;

        return [
            'titulo' => 'required|string|max:50',
            'mensagem' => 'required|string',
            'tipoNotificacao_id' => 'required|exists:padrao_tipo,id',
            'enviarNotificacaoPara_id' => 'nullable|in:15,16,17|exists:padrao_tipo,id',
            'icone' => 'nullable|string|max:30',
            'expira_em' => 'nullable|date|after:now',
            'enviado_para' => 'nullable|sting',
            'usuarios' => 'required_if:enviarNotificacaoPara_id,16|array',
            'usuarios.*' => 'exists:users,id',
            'perfis' => 'required_if:enviarNotificacaoPara_id,17|array',
            'perfis.*' => 'exists:perfil,id',
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => __('messages.notification.validation.title.required'),
            'titulo.string' => __('messages.notification.validation.title.string'),
            'titulo.max' => __('messages.notification.validation.title.max'),

            'mensagem.required' => __('messages.notification.validation.message.required'),
            'mensagem.string' => __('messages.notification.validation.message.string'),

            'tipoNotificacao_id.required' => __('messages.notification.validation.notification_type.required'),
            'tipoNotificacao_id.exists' => __('messages.notification.validation.notification_type.exists'),

            'enviarNotificacaoPara_id.required' => __('messages.notification.validation.sent_notification_to.required'),
            'enviarNotificacaoPara_id.exists' => __('messages.notification.validation.sent_notification_to.exists'),

            'icone.string' => __('messages.notification.validation.icon.string'),
            'icone.max' => __('messages.notification.validation.icon.max'),

            'expira_em.date' => __('messages.notification.validation.expired_at.date'),
            'expira_em.after' => __('messages.notification.validation.expired_at.after'),

            'enviado_para.string' => __('messages.notification.validation.send_to.required'),

            'usuarios.required_if' => __('messages.notification.validation.users.required_if'),
            'usuarios.array' => __('messages.notification.validation.users.array'),
            'usuarios.*.exists' => __('messages.notification.validation.users.exists'),

            'perfis.required_if' => __('messages.notification.validation.profiles.required_if'),
            'perfis.array' => __('messages.notification.validation.profiles.array'),
            'perfis.*.exists' => __('messages.notification.validation.profiles.exists'),
        ];
    }
}
