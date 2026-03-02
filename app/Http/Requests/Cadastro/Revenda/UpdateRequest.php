<?php

namespace App\Http\Requests\Cadastro\Revenda;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
        if ($this->has('cnpj')) {
            $this->merge([
                'cnpj' => preg_replace('/[^0-9]/', '', $this->input('cnpj')),
            ]);
        }
        if ($this->has('responsavelCpf')) {
            $this->merge([
                'responsavelCpf' => preg_replace('/[^0-9]/', '', $this->input('responsavelCpf')),
            ]);
        }

        // Tratar checkboxes como boolean: checked = 1, unchecked/not sent = 0
        $this->merge([
            'temJardim' => $this->filled('temJardim') ? 1 : 0,
            'descontoVitalicio' => $this->filled('descontoVitalicio') ? 1 : 0,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'tipoRevenda_id' => 'required|exists:padrao_tipo,id',
            'nomeFantasia' => 'required|string|max:80',
            'razaoSocial' => 'required|string|max:80',
            'matrizRevenda_id' => 'nullable|exists:revenda,id',
            'dataCriacao' => 'required|date',
            'cnpj' => 'required|string|size:14|unique:revenda,cnpj,' . $this->revenda->id,
            'inscricaoEstadual' => 'required|string|max:20',
            'inscricaoMunicipal' => 'required|string|max:20',
            'optanteSimples_id' => 'required|exists:padrao_tipo,id',
            'responsavelNome' => 'nullable|string|max:50',
            'responsavelRg' => 'nullable|string|max:15',
            'responsavelRgOrgaoEmissor' => 'nullable|string|max:15',
            'responsavelCpf' => 'nullable|string|size:11',
            'observacao' => 'nullable|string',
            'situacao_id' => 'required|exists:padrao_tipo,id',
            'nivelDesconto' => 'nullable|integer',
            'dataUltimaAlteracaoDesconto' => 'nullable|date',
            'descontoInicial' => 'nullable|numeric|min:0|max:100',
            'descontoMaximo' => 'nullable|numeric|min:0|max:100',
            'descontoAtual' => 'nullable|numeric|min:0|max:100',
            'token' => 'nullable|string|max:255',
            'dataAberturaRevenda' => 'required|date',
            'ramoAtividade' => 'required|string|max:100',
            'tipoRegime_id' => 'required|exists:padrao_tipo,id',
            'fornecedoresAudio' => 'nullable|string',
            'fornecedoresVideo' => 'nullable|string',
            'fornecedoresAutomacao' => 'nullable|string',
            'tipoShowroom_id' => 'required|exists:padrao_tipo,id',
            'areaExposicao' => 'required|numeric|min:0',
            'temJardim' => 'required|boolean',
            'descontoVitalicio' => 'required|boolean',
            'dataAprovacaoCadastro' => 'nullable|date',
            'origemCadastro_id' => 'required|exists:padrao_tipo,id',
        ];
    }

    /**
     * Get the custom validation messages.
     */
    public function messages(): array
    {
        return [
            'tipoRevenda_id.required' => __('messages.revenda.validation.tipoRevenda_id.required'),
            'tipoRevenda_id.exists' => __('messages.revenda.validation.tipoRevenda_id.exists'),

            'nomeFantasia.required' => __('messages.revenda.validation.nomeFantasia.required'),
            'nomeFantasia.string' => __('messages.revenda.validation.nomeFantasia.string'),
            'nomeFantasia.max' => __('messages.revenda.validation.nomeFantasia.max'),

            'razaoSocial.required' => __('messages.revenda.validation.razaoSocial.required'),
            'razaoSocial.string' => __('messages.revenda.validation.razaoSocial.string'),
            'razaoSocial.max' => __('messages.revenda.validation.razaoSocial.max'),

            'matrizRevenda_id.exists' => __('messages.revenda.validation.matrizRevenda_id.exists'),

            'dataCriacao.required' => __('messages.revenda.validation.dataCriacao.required'),
            'dataCriacao.date' => __('messages.revenda.validation.dataCriacao.date'),

            'cnpj.required' => __('messages.revenda.validation.cnpj.required'),
            'cnpj.string' => __('messages.revenda.validation.cnpj.string'),
            'cnpj.size' => __('messages.revenda.validation.cnpj.size'),
            'cnpj.unique' => __('messages.revenda.validation.cnpj.unique'),

            'inscricaoEstadual.required' => __('messages.revenda.validation.inscricaoEstadual.required'),
            'inscricaoEstadual.string' => __('messages.revenda.validation.inscricaoEstadual.string'),
            'inscricaoEstadual.max' => __('messages.revenda.validation.inscricaoEstadual.max'),

            'inscricaoMunicipal.required' => __('messages.revenda.validation.inscricaoMunicipal.required'),
            'inscricaoMunicipal.string' => __('messages.revenda.validation.inscricaoMunicipal.string'),
            'inscricaoMunicipal.max' => __('messages.revenda.validation.inscricaoMunicipal.max'),

            'optanteSimples_id.required' => __('messages.revenda.validation.optanteSimples_id.required'),
            'optanteSimples_id.exists' => __('messages.revenda.validation.optanteSimples_id.exists'),

            'responsavelNome.string' => __('messages.revenda.validation.responsavelNome.string'),
            'responsavelNome.max' => __('messages.revenda.validation.responsavelNome.max'),

            'responsavelRg.string' => __('messages.revenda.validation.responsavelRg.string'),
            'responsavelRg.max' => __('messages.revenda.validation.responsavelRg.max'),

            'responsavelRgOrgaoEmissor.string' => __('messages.revenda.validation.responsavelRgOrgaoEmissor.string'),
            'responsavelRgOrgaoEmissor.max' => __('messages.revenda.validation.responsavelRgOrgaoEmissor.max'),

            'responsavelCpf.string' => __('messages.revenda.validation.responsavelCpf.string'),
            'responsavelCpf.size' => __('messages.revenda.validation.responsavelCpf.size'),

            'observacao.string' => __('messages.revenda.validation.observacao.string'),

            'situacao_id.required' => __('messages.revenda.validation.situacao_id.required'),
            'situacao_id.exists' => __('messages.revenda.validation.situacao_id.exists'),

            'nivelDesconto.integer' => __('messages.revenda.validation.nivelDesconto.integer'),

            'dataUltimaAlteracaoDesconto.date' => __('messages.revenda.validation.dataUltimaAlteracaoDesconto.date'),

            'descontoInicial.numeric' => __('messages.revenda.validation.descontoInicial.numeric'),
            'descontoInicial.min' => __('messages.revenda.validation.descontoInicial.min'),
            'descontoInicial.max' => __('messages.revenda.validation.descontoInicial.max'),

            'descontoMaximo.numeric' => __('messages.revenda.validation.descontoMaximo.numeric'),
            'descontoMaximo.min' => __('messages.revenda.validation.descontoMaximo.min'),
            'descontoMaximo.max' => __('messages.revenda.validation.descontoMaximo.max'),

            'descontoAtual.numeric' => __('messages.revenda.validation.descontoAtual.numeric'),
            'descontoAtual.min' => __('messages.revenda.validation.descontoAtual.min'),
            'descontoAtual.max' => __('messages.revenda.validation.descontoAtual.max'),

            'token.string' => __('messages.revenda.validation.token.string'),
            'token.max' => __('messages.revenda.validation.token.max'),

            'dataAberturaRevenda.required' => __('messages.revenda.validation.dataAberturaRevenda.required'),
            'dataAberturaRevenda.date' => __('messages.revenda.validation.dataAberturaRevenda.date'),

            'ramoAtividade.required' => __('messages.revenda.validation.ramoAtividade.required'),
            'ramoAtividade.string' => __('messages.revenda.validation.ramoAtividade.string'),
            'ramoAtividade.max' => __('messages.revenda.validation.ramoAtividade.max'),

            'tipoRegime_id.required' => __('messages.revenda.validation.tipoRegime_id.required'),
            'tipoRegime_id.exists' => __('messages.revenda.validation.tipoRegime_id.exists'),

            'fornecedoresAudio.string' => __('messages.revenda.validation.fornecedoresAudio.string'),

            'fornecedoresVideo.string' => __('messages.revenda.validation.fornecedoresVideo.string'),

            'fornecedoresAutomacao.string' => __('messages.revenda.validation.fornecedoresAutomacao.string'),

            'tipoShowroom_id.required' => __('messages.revenda.validation.tipoShowroom_id.required'),
            'tipoShowroom_id.exists' => __('messages.revenda.validation.tipoShowroom_id.exists'),

            'areaExposicao.required' => __('messages.revenda.validation.areaExposicao.required'),
            'areaExposicao.numeric' => __('messages.revenda.validation.areaExposicao.numeric'),
            'areaExposicao.min' => __('messages.revenda.validation.areaExposicao.min'),

            'temJardim.required' => __('messages.revenda.validation.temJardim.required'),
            'temJardim.boolean' => __('messages.revenda.validation.temJardim.boolean'),

            'descontoVitalicio.required' => __('messages.revenda.validation.descontoVitalicio.required'),
            'descontoVitalicio.boolean' => __('messages.revenda.validation.descontoVitalicio.boolean'),

            'dataAprovacaoCadastro.date' => __('messages.revenda.validation.dataAprovacaoCadastro.date'),

            'origemCadastro_id.required' => __('messages.revenda.validation.origemCadastro_id.required'),
            'origemCadastro_id.exists' => __('messages.revenda.validation.origemCadastro_id.exists'),
        ];
    }
}
