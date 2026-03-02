<?php

namespace App\Http\Requests\Cadastro\Cliente;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Tools;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->canAccess('cadastro.cliente.update');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $clienteId = $this->route('cliente')->id;

        $rules = [
            // Campos obrigatórios sempre
            'tipoCliente_id' => 'required|exists:padrao_tipo,id',
            'nome' => 'required|string|max:80',
            'situacao_id' => 'required|exists:padrao_tipo,id',
            
            // Campos opcionais sempre
            'nomePreferencia' => 'nullable|string|max:80',
            'origem_id' => 'nullable|exists:padrao_tipo,id',
            'data_cadastro' => 'nullable|date',
            'limite_credito' => 'nullable|numeric|min:0|max:99999999.99',
            'data_limite_credite' => 'nullable|date|after_or_equal:data_cadastro',
            'observacoes' => 'nullable|string|max:150',
        ];

        // Regras específicas para Pessoa Física
        if ($this->isPessoaFisica()) {
            $rules = array_merge($rules, [
                'cpf' => 'required|string|formato_cpf|cpf|unique:cliente,cpf,' . $clienteId,
                'dataNascimento' => 'nullable|date|before:today',
                'rg' => 'nullable|string|max:20',
                'rgOrgaoEmissor' => 'nullable|string|max:20',
            ]);
        }

        // Regras específicas para Pessoa Jurídica
        if ($this->isPessoaJuridica()) {
            $rules = array_merge($rules, [
                'cnpj' => 'required|string|formato_cnpj|cnpj|unique:cliente,cnpj,' . $clienteId,
                'nomeFantasia' => 'nullable|string|max:80',
                'inscricaoEstadual' => 'nullable|string|max:14',
                'inscricaoMunicipal' => 'nullable|string|max:14',
                'optanteSimples_id' => 'nullable|exists:padrao_tipo,id',
                'responsavelNome' => 'nullable|string|max:80',
                'responsavelCpf' => 'nullable|string|formato_cpf|cpf',
                'responsavelRg' => 'nullable|string|max:20',
                'responsavelRgOrgaoEmissor' => 'nullable|string|max:20',
            ]);
        }

        // Validação para endereços (se enviados)
        if ($this->has('enderecos')) {
            foreach ($this->input('enderecos', []) as $index => $endereco) {
                $rules["enderecos.{$index}.tipoEndereco_id"] = 'required|exists:padrao_tipo,id';
                $rules["enderecos.{$index}.cep"] = 'required|string|formato_cep';
                $rules["enderecos.{$index}.logradouro"] = 'required|string|max:100';
                $rules["enderecos.{$index}.numero"] = 'required|string|max:10';
                $rules["enderecos.{$index}.bairro"] = 'required|string|max:50';
                $rules["enderecos.{$index}.cidade"] = 'required|string|max:50';
                $rules["enderecos.{$index}.estado"] = 'required|string|size:2';
                $rules["enderecos.{$index}.pais"] = 'nullable|string|max:50';
                $rules["enderecos.{$index}.complemento"] = 'nullable|string|max:50';
            }
        }

        // Validação para contatos (se enviados)
        if ($this->has('contatos')) {
            foreach ($this->input('contatos', []) as $index => $contato) {
                $rules["contatos.{$index}.tipoContato_id"] = 'required|exists:padrao_tipo,id';
                $rules["contatos.{$index}.telefone"] = 'nullable|string|max:15';
                $rules["contatos.{$index}.contato"] = 'required|string|max:100';
                $rules["contatos.{$index}.email"] = 'nullable|email|max:50';
                $rules["contatos.{$index}.observacoes"] = 'nullable|string|max:100';
            }
        }

        return $rules;
    }

    /**
     * Get the custom validation messages.
     */
    public function messages(): array
    {
        return [
            // Mensagens gerais
            'tipoCliente_id.required' => __('messages.customer.validation.tipoCliente_id.required'),
            'tipoCliente_id.exists' => __('messages.customer.validation.tipoCliente_id.exists'),
            'nome.required' => __('messages.customer.validation.nome.required'),
            'nome.string' => __('messages.customer.validation.nome.string'),
            'nome.max' => __('messages.customer.validation.nome.max'),
            'situacao_id.required' => __('messages.customer.validation.situacao_id.required'),
            'situacao_id.exists' => __('messages.customer.validation.situacao_id.exists'),
            
            // Mensagens Pessoa Física
            'cpf.required' => __('messages.customer.validation.cpf.required'),
            'cpf.string' => __('messages.customer.validation.cpf.string'),
            'cpf.formato_cpf' => __('messages.customer.validation.cpf.formato_cpf'),
            'cpf.cpf' => __('messages.customer.validation.cpf.cpf'),
            'cpf.unique' => __('messages.customer.validation.cpf.unique'),
            'dataNascimento.date' => __('messages.customer.validation.dataNascimento.date'),
            'dataNascimento.before' => __('messages.customer.validation.dataNascimento.before'),
            
            // Mensagens Pessoa Jurídica
            'cnpj.required' => __('messages.customer.validation.cnpj.required'),
            'cnpj.string' => __('messages.customer.validation.cnpj.string'),
            'cnpj.formato_cnpj' => __('messages.customer.validation.cnpj.formato_cnpj'),
            'cnpj.cnpj' => __('messages.customer.validation.cnpj.cnpj'),
            'cnpj.unique' => __('messages.customer.validation.cnpj.unique'),
            'nomeFantasia.max' => __('messages.customer.validation.nomeFantasia.max'),
            
            // Mensagens financeiras
            'limite_credito.numeric' => __('messages.customer.validation.limite_credito.numeric'),
            'limite_credito.min' => __('messages.customer.validation.limite_credito.min'),
            'limite_credito.max' => __('messages.customer.validation.limite_credito.max'),
            'data_limite_credite.after_or_equal' => __('messages.customer.validation.data_limite_credite.after_or_equal'),
            
            // Mensagens endereços
            'enderecos.*.tipoEndereco_id.required' => __('messages.customer.validation.endereco.tipoEndereco_id.required'),
            'enderecos.*.cep.required' => __('messages.customer.validation.endereco.cep.required'),
            'enderecos.*.cep.formato_cep' => __('messages.customer.validation.endereco.cep.formato_cep'),
            'enderecos.*.logradouro.required' => __('messages.customer.validation.endereco.logradouro.required'),
            'enderecos.*.numero.required' => __('messages.customer.validation.endereco.numero.required'),
            'enderecos.*.bairro.required' => __('messages.customer.validation.endereco.bairro.required'),
            'enderecos.*.cidade.required' => __('messages.customer.validation.endereco.cidade.required'),
            'enderecos.*.estado.required' => __('messages.customer.validation.endereco.estado.required'),
            'enderecos.*.estado.size' => __('messages.customer.validation.endereco.estado.size'),
            
            // Mensagens contatos
            'contatos.*.tipoContato_id.required' => __('messages.customer.validation.contato.tipoContato_id.required'),
            'contatos.*.contato.required' => __('messages.customer.validation.contato.contato.required'),
            'contatos.*.email.email' => __('messages.customer.validation.contato.email.email'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Limpar valor monetário
        if ($this->has('limite_credito')) {
            $this->merge([
                'limite_credito' => Tools::limparValor($this->input('limite_credito')),
            ]);
        }

        // Limpar CPF/CNPJ
        if ($this->has('cpf')) {
            $this->merge([
                'cpf' => preg_replace('/[^0-9]/', '', $this->input('cpf')),
            ]);
        }

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

        // Limpar CEPs dos endereços
        $enderecos = $this->input('enderecos', []);
        foreach ($enderecos as $index => $endereco) {
            if (isset($endereco['cep'])) {
                $enderecos[$index]['cep'] = preg_replace('/[^0-9]/', '', $endereco['cep']);
            }
        }
        $this->merge(['enderecos' => $enderecos]);

        // Limpar telefones dos contatos
        $contatos = $this->input('contatos', []);
        foreach ($contatos as $index => $contato) {
            if (isset($contato['telefone'])) {
                $contatos[$index]['telefone'] = preg_replace('/[^0-9]/', '', $contato['telefone']);
            }
        }
        $this->merge(['contatos' => $contatos]);
    }

    /**
     * Verifica se é Pessoa Física
     */
    private function isPessoaFisica(): bool
    {
        $tipoCliente = \App\Models\Sistema\PadraoTipo::find($this->input('tipoCliente_id'));
        return $tipoCliente && strtolower($tipoCliente->descricao) === 'pessoa física';
    }

    /**
     * Verifica se é Pessoa Jurídica
     */
    private function isPessoaJuridica(): bool
    {
        $tipoCliente = \App\Models\Sistema\PadraoTipo::find($this->input('tipoCliente_id'));
        return $tipoCliente && strtolower($tipoCliente->descricao) === 'pessoa jurídica';
    }
}
