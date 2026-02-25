<?php

namespace App\Services\Generators;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ViewGenerator
{
    private string $classe;
    private string $moduloPai;
    private array $data;

    public function __construct(string $classe, string $moduloPai, array $data)
    {
        $this->classe = $classe;
        $this->moduloPai = $moduloPai;
        $this->data = $data;
    }

    public function generate(): array
    {
        $files = [];
        $dir = resource_path("views/" . strtolower($this->moduloPai) . "/" . Str::kebab($this->classe));

        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $views = ['index', 'create', 'edit', 'show', 'destroy', 'history', 'form'];

        foreach ($views as $view) {
            try {
                $path = "{$dir}/{$view}.blade.php";
                $content = $this->getViewTemplate($view);
                File::put($path, $content);
                $files[] = "View: resources/views/" . strtolower($this->moduloPai) . "/" . Str::kebab($this->classe) . "/{$view}.blade.php";
            } catch (\Exception $e) {
                Log::error('Erro ao gerar view ' . $view . ': ' . $e->getMessage());
                throw $e;
            }
        }

        return $files;
    }

    private function getViewTemplate(string $view): string
    {
        $classeLower = strtolower($this->classe);
        $moduloLower = strtolower($this->moduloPai);
        $classeKebab = Str::kebab($this->classe);

        // Usar templates da pasta template/exemplo como base
        $templatePath = resource_path("views/template/exemplo/{$view}.blade.php");

        if (File::exists($templatePath)) {
            $template = File::get($templatePath);
            $replacements = [
                'template.exemplo' => "{$moduloLower}.{$classeLower}",
                'exemplo' => $classeLower,
                'exemplos' => $classeLower . 's',
                'Exemplo' => $this->classe,
                'labels.exemplo' => "labels.{$classeLower}",
                'exempo' => $classeLower, // Fix para history.blade.php
            ];

            return str_replace(array_keys($replacements), array_values($replacements), $template);
        }

        // Fallback para templates genéricos
        return $this->getGenericTemplate($view, $classeLower, $moduloLower, $classeKebab);
    }

    private function getGenericTemplate(string $view, string $classeLower, string $moduloLower, string $classeKebab): string
    {
        switch ($view) {
            case 'form':
                return $this->getFormTemplate();
            default:
                return "<!-- View: {$view} -->\n@extends('layouts.app')\n\n@section('content')\n    <div class='container'>\n        <h1>" . ucfirst($view) . " {$this->classe}</h1>\n        <!-- Content here -->\n    </div>\n@endsection";
        }
    }

    private function getFormTemplate(): string
    {
        $fieldsHtml = $this->generateFormFields();
        
        return "@extends('layouts.app')\n\n@section('content')\n<div class='container'>\n    <form method='POST' action='{{ request()->is(\"*create*\") ? route(\"" . strtolower($this->moduloPai) . "." . strtolower($this->classe) . ".store\") : route(\"" . strtolower($this->moduloPai) . "." . strtolower($this->classe) . ".update\", $" . strtolower($this->classe) . ") }}'>\n        @csrf\n        @if(!request()->is(\"*create*\"))\n            @method('PUT')\n        @endif\n        \n{$fieldsHtml}\n        \n        <div class='form-group'>\n            <button type='submit' class='btn btn-primary'>\n                {{ request()->is('*create*') ? __('Cadastrar') : __('Atualizar') }}\n            </button>\n            <a href='{{ route(\"" . strtolower($this->moduloPai) . "." . strtolower($this->classe) . ".index\") }}' class='btn btn-secondary'>\n                {{ __('Cancelar') }}\n            </a>\n        </div>\n    </form>\n</div>\n@endsection";
    }

    private function generateFormFields(): string
    {
        $campos = [];
        $totalCampos = count($this->data['campos']);
        $colSize = $totalCampos <= 2 ? 12 : ($totalCampos <= 4 ? 6 : 4);

        foreach ($this->data['campos'] as $index => $campo) {
            $nome = $campo['nome'];
            $tipo = $campo['tipo'];
            $obrigatorio = $campo['obrigatorio'] ?? false;
            $max = $campo['max'] ?? null;
            $unique = $campo['unique'] ?? false;
            $relacionamento = $campo['relacionamento'] ?? null;

            $required = $obrigatorio ? 'required' : '';
            $label = ucfirst($nome);
            $disabled = isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '';

            if ($index % 2 == 0) {
                $campos[] = '<div class="row">';
            }

            $html = "        <div class=\"form-group col-md-{$colSize}\">\n";
            $html .= "            <label for=\"{$nome}\">{$label}</label>\n";

            $html .= $this->generateFieldInput($nome, $tipo, $required, $disabled, $max, $relacionamento);

            $html .= "        </div>\n";

            if ($index % 2 == 1 || $index == count($this->data['campos']) - 1) {
                $html .= "    </div>\n";
            }

            $campos[] = $html;
        }

        return implode("\n", $campos);
    }

    private function generateFieldInput(string $nome, string $tipo, string $required, string $disabled, ?int $max, ?string $relacionamento): string
    {
        switch ($tipo) {
            case 'String':
            case 'Inteiro':
            case 'Decimal/Valor':
                $inputType = ($tipo == 'Decimal/Valor') ? 'number' : 'text';
                $step = ($tipo == 'Decimal/Valor') ? 'step="0.01"' : '';
                return "            <input type=\"{$inputType}\" {$step} class=\"form-control\" name=\"{$nome}\" id=\"{$nome}\" value=\"{{ old('{$nome}', \${$nome} ?? null) }}\" {$disabled} {$required}>\n";

            case 'Data':
                return "            <input type=\"date\" class=\"form-control\" name=\"{$nome}\" id=\"{$nome}\" value=\"{{ old('{$nome}', \${$nome} ?? null) }}\" {$disabled} {$required}>\n";

            case 'Booleano':
                return "            <div class=\"form-check\">\n                <input class=\"form-check-input\" type=\"checkbox\" name=\"{$nome}\" id=\"{$nome}\" value=\"1\" {{ old('{$nome}', \${$nome} ?? null) ? 'checked' : '' }} {$disabled}>\n                <label class=\"form-check-label\" for=\"{$nome}\">Sim</label>\n            </div>\n";

            case 'Texto Longo':
                return "            <textarea class=\"form-control\" name=\"{$nome}\" id=\"{$nome}\" rows=\"3\" {$disabled} {$required}>{{ old('{$nome}', \${$nome} ?? null) }}</textarea>\n";

            case 'Arquivo':
                return "            <input type=\"file\" class=\"form-control\" name=\"{$nome}\" id=\"{$nome}\" {$disabled}>\n";

            case 'Select/Relacionamento':
                if ($relacionamento && !empty($relacionamento)) {
                    $modelClass = $relacionamento;
                    $variableName = strtolower(Str::plural($relacionamento));
                    return "            <select class=\"form-control\" name=\"{$nome}\" id=\"{$nome}\" {$disabled} {$required}>\n                <option value=\"\">Selecione...</option>\n                @foreach(\${$variableName} as \${$relacionamento})\n                    <option value=\"{{ \${$relacionamento}->id }}\" {{ old('{$nome}', \${$nome} ?? null) == \${$relacionamento}->id ? 'selected' : '' }}>{{ \${$relacionamento}->nome ?? \${$relacionamento}->id }}</option>\n                @endforeach\n            </select>\n";
                }
                return "            <input type=\"text\" class=\"form-control\" name=\"{$nome}\" id=\"{$nome}\" value=\"{{ old('{$nome}', \${$nome} ?? null) }}\" {$disabled} {$required}>\n";

            default:
                return "            <input type=\"text\" class=\"form-control\" name=\"{$nome}\" id=\"{$nome}\" value=\"{{ old('{$nome}', \${$nome} ?? null) }}\" {$disabled} {$required}>\n";
        }
    }
}
