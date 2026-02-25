<?php

namespace App\Services\Generators;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RequestGenerator
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
        $dir = app_path("Http/Requests/{$this->moduloPai}/{$this->classe}");

        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        // StoreRequest
        $files[] = $this->generateStoreRequest($dir);

        // UpdateRequest
        $files[] = $this->generateUpdateRequest($dir);

        return $files;
    }

    private function generateStoreRequest(string $dir): string
    {
        $storePath = "{$dir}/StoreRequest.php";
        $rules = $this->generateValidationRules(false);
        $messages = $this->generateValidationMessages();
        $storeContent = $this->getRequestTemplate('StoreRequest', $rules, $messages);
        
        File::put($storePath, $storeContent);
        return "StoreRequest: app/Http/Requests/{$this->moduloPai}/{$this->classe}/StoreRequest.php";
    }

    private function generateUpdateRequest(string $dir): string
    {
        $updatePath = "{$dir}/UpdateRequest.php";
        $rules = $this->generateValidationRules(true);
        $messages = $this->generateValidationMessages();
        $updateContent = $this->getRequestTemplate('UpdateRequest', $rules, $messages);
        
        File::put($updatePath, $updateContent);
        return "UpdateRequest: app/Http/Requests/{$this->moduloPai}/{$this->classe}/UpdateRequest.php";
    }

    private function generateValidationRules(bool $isUpdate): string
    {
        $rules = [];

        foreach ($this->data['campos'] as $campo) {
            $nome = $campo['nome'];
            $tipo = $campo['tipo'];
            $obrigatorio = $campo['obrigatorio'] ?? false;
            $max = $campo['max'] ?? null;
            $unique = $campo['unique'] ?? false;
            $relacionamento = $campo['relacionamento'] ?? null;

            $regras = [];

            if ($obrigatorio && !$isUpdate) {
                $regras[] = 'required';
            } else {
                $regras[] = 'nullable';
            }

            $regras = array_merge($regras, $this->getTypeRules($tipo, $relacionamento));

            if ($unique) {
                $tabela = Str::snake($this->classe);
                if ($isUpdate) {
                    $regras[] = "unique:{$tabela},{$nome}," . '$this->' . Str::lower($this->classe) . '->id';
                } else {
                    $regras[] = "unique:{$tabela},{$nome}";
                }
            }

            if ($max && in_array($tipo, ['String', 'string'])) {
                $regras[] = "max:{$max}";
            }

            $rules[] = "            '{$nome}' => '" . implode('|', $regras) . "'";
        }

        return implode(",\n", $rules);
    }

    private function getTypeRules(string $tipo, ?string $relacionamento): array
    {
        switch ($tipo) {
            case 'String':
            case 'string':
                return ['string'];
            case 'Inteiro':
            case 'integer':
                return ['integer'];
            case 'Decimal/Valor':
            case 'double':
                return ['numeric', 'regex:/^\d+(\.\d{1,2})?$/'];
            case 'Data':
            case 'date':
                return ['date'];
            case 'Booleano':
            case 'boolean':
                return ['boolean'];
            case 'Texto Longo':
            case 'text':
                return ['string'];
            case 'Arquivo':
            case 'file':
                return ['file', 'max:10240'];
            case 'Select/Relacionamento':
            case 'select':
                if ($relacionamento && !empty($relacionamento)) {
                    $tabelaRelacionamento = Str::snake(Str::plural($relacionamento));
                    return ["exists:{$tabelaRelacionamento},id"];
                }
                return [];
            default:
                return [];
        }
    }

    private function generateValidationMessages(): string
    {
        $messages = [];
        $classeLower = strtolower($this->classe);

        foreach ($this->data['campos'] as $campo) {
            $nome = $campo['nome'];
            $tipo = $campo['tipo'];
            $obrigatorio = $campo['obrigatorio'] ?? false;
            $max = $campo['max'] ?? null;
            $unique = $campo['unique'] ?? false;
            $relacionamento = $campo['relacionamento'] ?? null;

            if ($obrigatorio) {
                $messages[] = "            '{$nome}.required' => __('messages.{$classeLower}.validation.{$nome}.required'),";
            }

            $messages = array_merge($messages, $this->getTypeMessages($tipo, $nome, $classeLower, $max, $relacionamento));

            if ($unique) {
                $messages[] = "            '{$nome}.unique' => __('messages.{$classeLower}.validation.{$nome}.unique'),";
            }
        }

        return implode("\n", array_unique($messages));
    }

    private function getTypeMessages(string $tipo, string $nome, string $classeLower, ?int $max, ?string $relacionamento): array
    {
        $messages = [];

        switch ($tipo) {
            case 'String':
            case 'string':
                $messages[] = "            '{$nome}.string' => __('messages.{$classeLower}.validation.{$nome}.string'),";
                if ($max) {
                    $messages[] = "            '{$nome}.max' => __('messages.{$classeLower}.validation.{$nome}.max'),";
                }
                break;
            case 'Inteiro':
            case 'integer':
                $messages[] = "            '{$nome}.integer' => __('messages.{$classeLower}.validation.{$nome}.integer'),";
                break;
            case 'Decimal/Valor':
            case 'double':
                $messages[] = "            '{$nome}.numeric' => __('messages.{$classeLower}.validation.{$nome}.numeric'),";
                $messages[] = "            '{$nome}.regex' => __('messages.{$classeLower}.validation.{$nome}.regex'),";
                break;
            case 'Data':
            case 'date':
                $messages[] = "            '{$nome}.date' => __('messages.{$classeLower}.validation.{$nome}.date'),";
                break;
            case 'Booleano':
            case 'boolean':
                $messages[] = "            '{$nome}.boolean' => __('messages.{$classeLower}.validation.{$nome}.boolean'),";
                break;
            case 'Texto Longo':
            case 'text':
                $messages[] = "            '{$nome}.string' => __('messages.{$classeLower}.validation.{$nome}.string'),";
                break;
            case 'Arquivo':
            case 'file':
                $messages[] = "            '{$nome}.file' => __('messages.{$classeLower}.validation.{$nome}.file'),";
                $messages[] = "            '{$nome}.max' => __('messages.{$classeLower}.validation.{$nome}.max'),";
                break;
            case 'Select/Relacionamento':
            case 'select':
                if ($relacionamento && !empty($relacionamento)) {
                    $messages[] = "            '{$nome}.exists' => __('messages.{$classeLower}.validation.{$nome}.exists'),";
                }
                break;
        }

        return $messages;
    }

    private function getRequestTemplate(string $requestType, string $rules, string $messages): string
    {
        return "<?php\n\nnamespace App\\Http\\Requests\\{$this->moduloPai}\\{$this->classe};\n\nuse Illuminate\\Foundation\\Http\\FormRequest;\n\nclass {$requestType} extends FormRequest\n{\n    /**\n     * Determine if the user is authorized to make this request.\n     */\n    public function authorize(): bool\n    {\n        return true;\n    }\n\n    /**\n     * Get the validation rules that apply to the request.\n     *\n     * @return array<string, \\Illuminate\\Contracts\\Validation\\ValidationRule|array<mixed>|string>\n     */\n    public function rules(): array\n    {\n        return [\n{$rules}\n        ];\n    }\n\n    public function messages(): array\n    {\n        return [\n{$messages}\n        ];\n    }\n}";
    }
}
