<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class GeradorService
{
    private $data;
    private $classe;
    private $moduloPai;
    private $tabela;
    private $namespace;

    public function gerarCadastro(array $data): array
    {
        $this->data = $data;
        $this->classe = $data['classe'];
        $this->moduloPai = $data['modulo_pai'];
        $this->tabela = Str::snake($this->classe); // Usar exatamente o nome da classe
        $this->namespace = 'App\\Http\\Controllers\\' . $this->moduloPai;

        $resultado = [
            'files' => [],
            'permissoes' => [],
            'menu' => null
        ];

        // Gerar arquivos
        $resultado['files'] = array_merge(
            $this->gerarMigration(),
            $this->gerarModel(),
            $this->gerarController(),
            $this->gerarRequests(),
            $this->gerarViews(),
            $this->gerarObserver()
        );

        // Executar migration automaticamente
        \Illuminate\Support\Facades\Artisan::call('migrate', [
            '--force' => true
        ]);

        // Gerar permissões
        if ($data['criar_permissoes']) {
            $resultado['permissoes'] = $this->gerarPermissoes();
        }

        // Gerar menu
        if ($data['criar_menu']) {
            $resultado['menu'] = $this->gerarMenu();
        }

        // Registrar rotas
        $this->registrarRotas();

        return $resultado;
    }

    private function gerarMigration(): array
    {
        $timestamp = date('Y_m_d_His');
        $filename = "{$timestamp}_create_{$this->tabela}_table.php";
        $path = database_path("migrations/{$filename}");

        $campos = '';
        foreach ($this->data['campos'] as $campo) {
            $campos .= $this->gerarCampoMigration($campo);
        }

        $content = $this->getTemplateMigration($this->tabela, $campos, $this->data);

        File::put($path, $content);
        return ["Migration: {$filename}"];
    }

    private function gerarCampoMigration(array $campo): string
    {
        $nome = $campo['nome'];
        $tipo = $campo['tipo'];
        $obrigatorio = $campo['obrigatorio'] ?? false;
        $max = $campo['max'] ?? null;
        $unique = $campo['unique'] ?? false;
        $relacionamento = $campo['relacionamento'] ?? null; // Garante que exista

        $line = "            \$table->";

        // Mapear tipos
        switch ($tipo) {
            case 'string':
                $line .= $max ? "string('{$nome}', {$max})" : "string('{$nome}')";
                break;
            case 'integer':
                $line .= "integer('{$nome}')";
                break;
            case 'double':
                $line .= "decimal('{$nome}', 10, 2)";
                break;
            case 'date':
                $line .= "date('{$nome}')";
                break;
            case 'boolean':
                $line .= "boolean('{$nome}')";
                break;
            case 'text':
                $line .= "text('{$nome}')";
                break;
            case 'file':
                $line .= "string('{$nome}')";
                break;
            case 'select':
                $line .= "foreignId('{$nome}')->nullable()";
                if ($relacionamento && !empty($relacionamento)) {
                    $tabelaRelacionamento = Str::snake(Str::plural($relacionamento));
                    $line .= "->constrained('{$tabelaRelacionamento}')";
                }
                break;
        }

        if ($obrigatorio && $tipo !== 'select') {
            $line = str_replace(')', ')->notNullable()', $line);
        }

        if ($unique) {
            $line .= "->unique()";
        }

        $line .= ";\n            ";

        return $line;
    }

    private function gerarModel(): array
    {
        $path = app_path("Models/{$this->classe}.php");

        $fillable = [];
        $casts = [];
        $relationships = [];

        foreach ($this->data['campos'] as $campo) {
            $fillable[] = "'{$campo['nome']}'";

            if ($campo['tipo'] === 'boolean') {
                $casts[] = "'{$campo['nome']}' => 'boolean'";
            } elseif ($campo['tipo'] === 'date') {
                $casts[] = "'{$campo['nome']}' => 'date'";
            } elseif ($campo['tipo'] === 'double') {
                $casts[] = "'{$campo['nome']}' => 'decimal:2'";
            }

            if ($campo['relacionamento']) {
                $relationships[] = $this->gerarRelationship($campo);
            }
        }

        $content = $this->getTemplateModel(
            $this->classe,
            $this->tabela,
            $fillable,
            $casts,
            $relationships,
            $this->data
        );

        File::put($path, $content);
        return ["Model: app/Models/{$this->classe}.php"];
    }

    private function gerarController(): array
    {
        $path = app_path("Http/Controllers/{$this->moduloPai}/{$this->classe}Controller.php");

        // Criar diretório se não existir
        $dir = dirname($path);
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $content = $this->getTemplateController(
            $this->classe,
            $this->moduloPai,
            $this->tabela,
            $this->data
        );

        File::put($path, $content);
        return ["Controller: app/Http/Controllers/{$this->moduloPai}/{$this->classe}Controller.php"];
    }

    private function gerarRequests(): array
    {
        $files = [];
        $dir = app_path("Http/Requests/{$this->moduloPai}/{$this->classe}");

        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        // StoreRequest
        $storePath = "{$dir}/StoreRequest.php";
        $storeContent = $this->getTemplateStoreRequest($this->classe, $this->moduloPai, $this->data);
        File::put($storePath, $storeContent);
        $files[] = "StoreRequest: app/Http/Requests/{$this->moduloPai}/{$this->classe}/StoreRequest.php";

        // UpdateRequest
        $updatePath = "{$dir}/UpdateRequest.php";
        $updateContent = $this->getTemplateUpdateRequest($this->classe, $this->moduloPai, $this->data);
        File::put($updatePath, $updateContent);
        $files[] = "UpdateRequest: app/Http/Requests/{$this->moduloPai}/{$this->classe}/UpdateRequest.php";

        return $files;
    }

    private function gerarViews(): array
    {
        $files = [];
        $dir = resource_path("views/" . strtolower($this->moduloPai) . "/" . Str::kebab($this->classe));

        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $views = ['index', 'create', 'edit', 'show', 'destroy', 'history', 'form'];

        foreach ($views as $view) {
            $path = "{$dir}/{$view}.blade.php";
            $content = $this->getTemplateView($view, $this->classe, $this->moduloPai, $this->data);
            File::put($path, $content);
            $files[] = "View: resources/views/{$this->moduloPai}/" . Str::kebab($this->classe) . "/{$view}.blade.php";
        }

        return $files;
    }

    private function gerarObserver(): array
    {
        $path = app_path("Observers/{$this->classe}Observer.php");
        $historicoModel = $this->classe . 'Historico';

        $content = $this->getTemplateObserver($this->classe, $historicoModel, $this->moduloPai);

        File::put($path, $content);
        return ["Observer: app/Observers/{$this->classe}Observer.php"];
    }

    private function gerarPermissoes(): array
    {
        $permissoes = [];
        $actions = ['index', 'create', 'edit', 'show', 'destroy', 'history'];

        foreach ($actions as $action) {
            $permissao = strtolower($this->moduloPai) . '.' . strtolower($this->classe) . '.' . $action;

            DB::table('permissao')->insert([
                'descricao' => $permissao,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $permissoes[] = $permissao;
        }

        return $permissoes;
    }

    private function gerarMenu(): array
    {
        $permissaoIndex = strtolower($this->moduloPai) . '.' . strtolower($this->classe) . '.index';
        $permissao = DB::table('permissao')->where('descricao', $permissaoIndex)->first();

        if (!$permissao) {
            return [];
        }

        $menuData = [
            'descricao' => $this->classe, // Usar exatamente o nome da classe
            'icone' => 'fas fa-database',
            'rota' => $permissaoIndex,
            'menuPai_id' => null,
            'permissao_id' => $permissao->id,
            'situacao_id' => 1, // Ativo
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('menu')->insert($menuData);

        return $menuData;
    }

    private function registrarRotas(): void
    {
        $routeFile = base_path('routes/web.php');
        $routeContent = File::get($routeFile);

        $newRoute = $this->getTemplateRoute($this->classe, $this->moduloPai);

        // Adicionar rotas antes da última linha
        $routeContent = str_replace("});", $newRoute . "\n});", $routeContent);

        File::put($routeFile, $routeContent);
    }

    private function gerarRelationship($campo)
    {
        $relacionamento = $campo['relacionamento'] ?? null;

        if (!$relacionamento || empty($relacionamento)) return '';

        $nomeRelacionamento = $campo['nome'];
        $classeRelacionamento = $relacionamento;

        $relationship = "    public function " . Str::camel(Str::plural($nomeRelacionamento)) . "(): BelongsTo\n";
        $relationship .= "    {\n";
        $relationship .= "        return \$this->belongsTo({$classeRelacionamento}::class, '{$nomeRelacionamento}');\n";
        $relationship .= "    }\n\n";

        return $relationship;
    }

    private function getTemplateMigration($tabela, $campos, $data)
    {
        $softDeletes = $data['soft_delete'] ? "\$table->softDeletes();\n" : "";
        $timestamps = $data['timestamps'] !== false ? "\$table->timestamps();\n" : "";

        return "<?php\n\nuse Illuminate\\Database\\Migrations\\Migration;\nuse Illuminate\\Database\\Schema\\Blueprint;\nuse Illuminate\\Support\\Facades\\Schema;\n\nreturn new class extends Migration\n{\n    /**\n     * Run the migrations.\n     */\n    public function up(): void\n    {\n        Schema::create('{$tabela}', function (Blueprint \$table) {\n            \$table->id();\n{$campos}{$softDeletes}{$timestamps}\n        });\n    }\n\n    /**\n     * Reverse the migrations.\n     */\n    public function down(): void\n    {\n        Schema::dropIfExists('{$tabela}');\n    }\n};";
    }

    private function getTemplateModel($classe, $tabela, $fillable, $casts, $relationships, $data)
    {
        $softDeletes = $data['soft_delete'] ? 'use Illuminate\\Database\\Eloquent\\SoftDeletes;' : '';
        $softDeletesTrait = $data['soft_delete'] ? 'SoftDeletes' : '';

        $fillableStr = implode(', ', $fillable);
        $castsStr = !empty($casts) ? "\n    protected \$casts = [\n        " . implode(",\n        ", $casts) . "\n    ];" : '';
        $relationshipsStr = !empty($relationships) ? "\n" . implode('', $relationships) : '';

        return "<?php\n\nnamespace App\\Models;\n\nuse Illuminate\\Database\\Eloquent\\Factories\\HasFactory;\nuse Illuminate\\Database\\Eloquent\\Model;\nuse Illuminate\\Database\\Eloquent\\Relations\\BelongsTo;\n{$softDeletes}\n\nclass {$classe} extends Model\n{\n    use HasFactory, {$softDeletesTrait};\n\n    protected \$table = '{$tabela}';\n\n    protected \$fillable = [\n        {$fillableStr}\n    ];{$castsStr}{$relationshipsStr}\n}";
    }

    private function getTemplateController($classe, $moduloPai, $tabela, $data)
    {
        $namespace = $this->namespace;
        $moduloLower = strtolower($moduloPai);
        $classeLower = strtolower($classe);

        return "<?php\n\nnamespace {$namespace};\n\nuse App\\Http\\Controllers\\Controller;\nuse App\\Models\\{$classe};\nuse App\\Http\\Requests\\{$moduloPai}\\{$classe}\\StoreRequest;\nuse App\\Http\\Requests\\{$moduloPai}\\{$classe}\\UpdateRequest;\nuse Illuminate\\Http\\Request;\nuse Illuminate\\Support\\Facades\\Auth;\n\nclass {$classe}Controller extends Controller\n{\n    /**\n     * Display a listing of the resource.\n     */\n    public function index()\n    {\n        abort_if (!Auth::user()->canAccess('{$moduloLower}.{$classeLower}.index'), 403, 'Acesso não autorizado');\n\n        \${$classeLower}s = {$classe}::paginate();\n        return view('{$moduloLower}." . Str::kebab($classe) . ".index', compact('{$classeLower}s'));\n    }\n\n    /**\n     * Show the form for creating a new resource.\n     */\n    public function create()\n    {\n        abort_if (!Auth::user()->canAccess('{$moduloLower}.{$classeLower}.create'), 403, 'Acesso não autorizado');\n\n        return view('{$moduloLower}." . Str::kebab($classe) . ".create');\n    }\n\n    /**\n     * Store a newly created resource in storage.\n     */\n    public function store(StoreRequest \$request)\n    {\n        \${$classeLower} = {$classe}::create(\$request->validated());\n\n        if(\${$classeLower}) {\n            return redirect()->signedRoute('{$moduloLower}.{$classeLower}.index')->with('success', __('labels.{$classeLower}.success.created'));\n        }\n        return redirect()->signedRoute('{$moduloLower}.{$classeLower}.index')->with('error', __('labels.{$classeLower}.error.not_saved'));\n    }\n\n    /**\n     * Display the specified resource.\n     */\n    public function show({$classe} \${$classeLower})\n    {\n        abort_if (!Auth::user()->canAccess('{$moduloLower}.{$classeLower}.show'), 403, 'Acesso não autorizado');\n\n        \$bloquearCampos = true;\n        return view('{$moduloLower}." . Str::kebab($classe) . ".show', compact('{$classeLower}', 'bloquearCampos'));\n    }\n\n    /**\n     * Show the form for editing the specified resource.\n     */\n    public function edit({$classe} \${$classeLower})\n    {\n        abort_if (!Auth::user()->canAccess('{$moduloLower}.{$classeLower}.edit'), 403, 'Acesso não autorizado');\n\n        return view('{$moduloLower}." . Str::kebab($classe) . ".edit', compact('{$classeLower}'));\n    }\n\n    /**\n     * Update the specified resource in storage.\n     */\n    public function update(UpdateRequest \$request, {$classe} \${$classeLower})\n    {\n        if(\${$classeLower}->update(\$request->validated())) {\n            return redirect()->signedRoute('{$moduloLower}.{$classeLower}.index')->with('success', __('labels.{$classeLower}.success.updated'));\n        }\n        return redirect()->signedRoute('{$moduloLower}.{$classeLower}.index')->with('error', __('labels.{$classeLower}.error.not_updated'));\n    }\n\n    /**\n     * Remove the specified resource from storage.\n     */\n    public function destroy({$classe} \${$classeLower})\n    {\n        abort_if (!Auth::user()->canAccess('{$moduloLower}.{$classeLower}.destroy'), 403, 'Acesso não autorizado');\n\n        \$bloquearCampos = true;\n        return view('{$moduloLower}." . Str::kebab($classe) . ".destroy', compact('{$classeLower}', 'bloquearCampos'));\n    }\n\n    /**\n     * Delete the specified resource from storage.\n     */\n    public function delete({$classe} \${$classeLower})\n    {\n        if(\${$classeLower}->delete()) {\n            return redirect()->signedRoute('{$moduloLower}.{$classeLower}.index')->with('success', __('labels.{$classeLower}.success.deleted'));\n        }\n        return redirect()->signedRoute('{$moduloLower}.{$classeLower}.index')->with('error', __('labels.{$classeLower}.error.not_deleted'));\n    }\n\n    /**\n     * Display the history of the specified resource.\n     */\n    public function history({$classe} \${$classeLower})\n    {\n        abort_if (!Auth::user()->canAccess('{$moduloLower}.{$classeLower}.history'), 403, 'Acesso não autorizado');\n\n        \$bloquearCampos = true;\n        return view('{$moduloLower}." . Str::kebab($classe) . ".history', compact('{$classeLower}', 'bloquearCampos'));\n    }\n}";
    }

    private function getTemplateStoreRequest($classe, $moduloPai, $data)
    {
        $rules = $this->gerarRegrasValidacao($data, false);
        $messages = $this->gerarMensagensValidacao($classe, $data);

        return "<?php\n\nnamespace App\\Http\\Requests\\{$moduloPai}\\{$classe};\n\nuse Illuminate\\Foundation\\Http\\FormRequest;\n\nclass StoreRequest extends FormRequest\n{\n    /**\n     * Determine if the user is authorized to make this request.\n     */\n    public function authorize(): bool\n    {\n        return true;\n    }\n\n    /**\n     * Get the validation rules that apply to the request.\n     *\n     * @return array<string, \\Illuminate\\Contracts\\Validation\\ValidationRule|array<mixed>|string>\n     */\n    public function rules(): array\n    {\n        return [\n{$rules}\n        ];\n    }\n\n    public function messages(): array\n    {\n        return [\n{$messages}\n        ];\n    }\n}";
    }

    private function getTemplateUpdateRequest($classe, $moduloPai, $data)
    {
        $rules = $this->gerarRegrasValidacao($data, true);
        $messages = $this->gerarMensagensValidacao($classe, $data);

        return "<?php\n\nnamespace App\\Http\\Requests\\{$moduloPai}\\{$classe};\n\nuse Illuminate\\Foundation\\Http\\FormRequest;\n\nclass UpdateRequest extends FormRequest\n{\n    /**\n     * Determine if the user is authorized to make this request.\n     */\n    public function authorize(): bool\n    {\n        return true;\n    }\n\n    /**\n     * Get the validation rules that apply to the request.\n     *\n     * @return array<string, \\Illuminate\\Contracts\\Validation\\ValidationRule|array<mixed>|string>\n     */\n    public function rules(): array\n    {\n        return [\n{$rules}\n        ];\n    }\n\n    public function messages(): array\n    {\n        return [\n{$messages}\n        ];\n    }\n}";
    }

    private function gerarRegrasValidacao($data, $isUpdate = false)
    {
        $rules = [];

        foreach ($data['campos'] as $campo) {
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

            // Mapear tipos para regras
            switch ($tipo) {
                case 'string':
                    $regras[] = 'string';
                    if ($max) $regras[] = "max:{$max}";
                    break;
                case 'integer':
                    $regras[] = 'integer';
                    break;
                case 'double':
                    $regras[] = 'numeric';
                    $regras[] = 'regex:/^\d+(\.\d{1,2})?$/';
                    break;
                case 'date':
                    $regras[] = 'date';
                    break;
                case 'boolean':
                    $regras[] = 'boolean';
                    break;
                case 'text':
                    $regras[] = 'string';
                    break;
                case 'file':
                    $regras[] = 'file';
                    $regras[] = 'max:10240'; // 10MB
                    break;
                case 'select':
                    if ($relacionamento && !empty($relacionamento)) {
                        $tabelaRelacionamento = Str::snake(Str::plural($relacionamento));
                        $regras[] = "exists:{$tabelaRelacionamento},id";
                    }
                    break;
            }

            if ($unique) {
                $tabela = Str::snake($this->classe); // Usar exatamente o nome da classe
                if ($isUpdate) {
                    $regras[] = "unique:{$tabela},{$nome}," . '$this->' . Str::lower($this->classe) . '->id';
                } else {
                    $regras[] = "unique:{$tabela},{$nome}";
                }
            }

            $rules[] = "            '{$nome}' => '" . implode('|', $regras) . "'";
        }

        return implode(",\n", $rules);
    }

    private function gerarMensagensValidacao($classe, $data)
    {
        $messages = [];
        $classeLower = strtolower($classe);

        foreach ($data['campos'] as $campo) {
            $nome = $campo['nome'];
            $tipo = $campo['tipo'];

            $messages[] = "            '{$nome}.required' => __('messages.{$classeLower}.validation.{$nome}.required'),";
            $messages[] = "            '{$nome}.string' => __('messages.{$classeLower}.validation.{$nome}.string'),";
            $messages[] = "            '{$nome}.integer' => __('messages.{$classeLower}.validation.{$nome}.integer'),";
            $messages[] = "            '{$nome}.numeric' => __('messages.{$classeLower}.validation.{$nome}.numeric'),";
            $messages[] = "            '{$nome}.regex' => __('messages.{$classeLower}.validation.{$nome}.regex'),";
            $messages[] = "            '{$nome}.date' => __('messages.{$classeLower}.validation.{$nome}.date'),";
            $messages[] = "            '{$nome}.boolean' => __('messages.{$classeLower}.validation.{$nome}.boolean'),";
            $messages[] = "            '{$nome}.max' => __('messages.{$classeLower}.validation.{$nome}.max'),";
            $messages[] = "            '{$nome}.unique' => __('messages.{$classeLower}.validation.{$nome}.unique'),";
            $messages[] = "            '{$nome}.exists' => __('messages.{$classeLower}.validation.{$nome}.exists'),";
        }

        return implode("\n", array_unique($messages));
    }

    private function getTemplateView($view, $classe, $moduloPai, $data)
    {
        $classeLower = strtolower($classe);
        $moduloLower = strtolower($moduloPai);
        $classeKebab = Str::kebab($classe);

        switch ($view) {
            case 'index':
                return $this->getTemplateIndexView($classe, $classeLower, $moduloLower, $classeKebab);
            case 'create':
                return $this->getTemplateCreateView($classe, $classeLower, $moduloLower, $classeKebab);
            case 'edit':
                return $this->getTemplateEditView($classe, $classeLower, $moduloLower, $classeKebab);
            case 'form':
                return $this->getTemplateFormView($classe, $classeLower, $moduloLower, $data);
            default:
                return $this->getTemplateGenericView($view, $classe, $classeLower, $moduloLower, $classeKebab);
        }
    }

    private function getTemplateIndexView($classe, $classeLower, $moduloLower, $classeKebab)
    {
        $title = Str::plural($classe);
        $variable = $classeLower;
        $variablePlural = $classeLower . 's';
        $permissions = [
            'create' => "{$moduloLower}.{$classeLower}.create",
            'show' => "{$moduloLower}.{$classeLower}.show",
            'edit' => "{$moduloLower}.{$classeLower}.edit",
            'destroy' => "{$moduloLower}.{$classeLower}.destroy"
        ];
        $routes = [
            'create' => "{$moduloLower}.{$classeLower}.create",
            'show' => "{$moduloLower}.{$classeLower}.show",
            'edit' => "{$moduloLower}.{$classeLower}.edit",
            'destroy' => "{$moduloLower}.{$classeLower}.destroy"
        ];

        return view('templates.index', compact('title', 'variable', 'variablePlural', 'permissions', 'routes'))->render();
    }

    private function getTemplateCreateView($classe, $classeLower, $moduloLower, $classeKebab)
    {
        $title = $classe;
        $formView = "{$moduloLower}.{$classeKebab}.form";

        return view('templates.create', compact('title', 'formView'))->render();
    }

    private function getTemplateEditView($classe, $classeLower, $moduloLower, $classeKebab)
    {
        $title = $classe;
        $formView = "{$moduloLower}.{$classeKebab}.form";

        return view('templates.edit', compact('title', 'formView'))->render();
    }

    private function getTemplateFormView($classe, $classeLower, $moduloLower, $data)
    {
        $camposHtml = $this->gerarCamposForm($data);
        $route = "{$moduloLower}.{$classeLower}.store";
        $backRoute = "{$moduloLower}.{$classeLower}.index";

        return view('templates.form', compact('camposHtml', 'route', 'backRoute'))->render();
    }

    private function gerarCamposForm($data)
    {
        $campos = [];

        foreach ($data['campos'] as $index => $campo) {
            $nome = $campo['nome'];
            $tipo = $campo['tipo'];
            $obrigatorio = $campo['obrigatorio'] ?? false;
            $max = $campo['max'] ?? null;
            $relacionamento = $campo['relacionamento'] ?? null;

            $required = $obrigatorio ? 'required' : '';
            $label = ucfirst($nome);

            $html = "        <div class=\"col-md-6\">\n";
            $html .= "            <div class=\"form-group\">\n";
            $html .= "                <label for=\"{$nome}\">{$label}</label>\n";

            switch ($tipo) {
                case 'string':
                case 'integer':
                case 'double':
                    $html .= "                <input type=\"text\" class=\"form-control\" name=\"{$nome}\" id=\"{$nome}\" value=\"{{ old('{$nome}') }}\" {$required}>\n";
                    break;
                case 'date':
                    $html .= "                <input type=\"date\" class=\"form-control\" name=\"{$nome}\" id=\"{$nome}\" value=\"{{ old('{$nome}') }}\" {$required}>\n";
                    break;
                case 'boolean':
                    $html .= "                <select class=\"form-control\" name=\"{$nome}\" id=\"{$nome}\" {$required}>\n";
                    $html .= "                    <option value=\"1\">Sim</option>\n";
                    $html .= "                    <option value=\"0\">Não</option>\n";
                    $html .= "                </select>\n";
                    break;
                case 'text':
                    $html .= "                <textarea class=\"form-control\" name=\"{$nome}\" id=\"{$nome}\" rows=\"3\" {$required}>{{ old('{$nome}') }}</textarea>\n";
                    break;
                case 'file':
                    $html .= "                <input type=\"file\" class=\"form-control\" name=\"{$nome}\" id=\"{$nome}\" {$required}>\n";
                    break;
                case 'select':
                    $html .= "                <select class=\"form-control\" name=\"{$nome}\" id=\"{$nome}\" {$required}>\n";
                    $html .= "                    <option value=\"\">Selecione...</option>\n";
                    if ($relacionamento && !empty($relacionamento)) {
                        // TODO: Carregar opções do relacionamento
                        $html .= "                    {{-- TODO: Carregar \${$relacionamento}s do relacionamento --}}\n";
                    }
                    $html .= "                </select>\n";
                    break;
            }

            $html .= "                @error('{$nome}')\n";
            $html .= "                    <div class=\"invalid-feedback\">{{ \$message }}</div>\n";
            $html .= "                @enderror\n";
            $html .= "            </div>\n";
            $html .= "        </div>\n";

            $campos[] = $html;
        }

        return implode("\n", $campos);
    }

    private function getTemplateGenericView($view, $classe, $classeLower, $moduloLower, $classeKebab)
    {
        $title = $classe;
        $action = ucfirst($view);

        return view('templates.show', compact('title', 'action'))->render();
    }

    private function getTemplateObserver($classe, $historicoModel, $moduloPai)
    {
        return "<?php\n\nnamespace App\\Observers;\n\nuse App\\Models\\{$classe};\nuse App\\Models\\{$historicoModel};\nuse App\\Models\\PadraoTipo;\nuse Illuminate\\Support\\Facades\\Auth;\n\nclass {$classe}Observer\n{\n    /**\n     * Handle the {$classe} \"created\" event.\n     */\n    public function created({$classe} \${$classe}): void\n    {\n        \$this->saveHistory(\${$classe}, null, \${$classe}->toArray(), 'Inclusão de Registro');\n    }\n\n    /**\n     * Handle the {$classe} \"updated\" event.\n     */\n    public function updated({$classe} \${$classe}): void\n    {\n        \$dadosAnteriores = \${$classe}->getOriginal();\n        \$dadosNovos = \${$classe}->fresh()->toArray();\n\n        \$this->saveHistory(\${$classe}, \$dadosAnteriores, \$dadosNovos, 'Alteração de Registro');\n    }\n\n    /**\n     * Handle the {$classe} \"deleted\" event.\n     */\n    public function deleted({$classe} \${$classe}): void\n    {\n        \$dadosAnteriores = \${$classe}->getOriginal();\n        \$this->saveHistory(\${$classe}, \$dadosAnteriores, null, 'Deleção de Registro');\n    }\n\n    /**\n     * Salva o registro no histórico.\n     */\n    protected function saveHistory({$classe} \${$classe}, ?array \$dadosAnteriores, ?array \$dadosNovos, string \$tipoDescricao): void\n    {\n        \$tipoAlteracao = PadraoTipo::where('descricao', \$tipoDescricao)->first();\n\n        {$historicoModel}::create([\n            'user_id' => Auth::id(),\n            '{$classe}_id' => \${$classe}->id,\n            'dados_anteriores' => \$dadosAnteriores,\n            'dados_novos' => \$dadosNovos,\n            'tipoAlteracao_id' => \$tipoAlteracao ? \$tipoAlteracao->id : null,\n        ]);\n    }\n}";
    }

    private function getTemplateRoute($classe, $moduloPai)
    {
        $moduloLower = strtolower($moduloPai);
        $classeLower = strtolower($classe);

        return "\n        # ROTAS DE " . strtoupper($classe) . "\n        Route::prefix('{$classeLower}')->name('{$classeLower}.')->group(function () {\n            Route::get('/', [\\{$moduloPai}\\{$classe}Controller::class, 'index'])->name('index');\n            Route::get('/create', [\\{$moduloPai}\\{$classe}Controller::class, 'create'])->name('create');\n            Route::post('/', [\\{$moduloPai}\\{$classe}Controller::class, 'store'])->name('store');\n\n            Route::get('/{ {$classeLower} }/edit', [\\{$moduloPai}\\{$classe}Controller::class, 'edit'])->name('edit');\n            Route::put('/{ {$classeLower} }', [\\{$moduloPai}\\{$classe}Controller::class, 'update'])->name('update');\n            Route::get('/{ {$classeLower} }/destroy', [\\{$moduloPai}\\{$classe}Controller::class, 'destroy'])->name('destroy');\n            Route::delete('/{ {$classeLower} }', [\\{$moduloPai}\\{$classe}Controller::class, 'delete'])->name('delete');\n            Route::get('/{ {$classeLower} }/history', [\\{$moduloPai}\\{$classe}Controller::class, 'history'])->name('history');\n            Route::get('/{ {$classeLower} }/history/{historico}/details', [\\{$moduloPai}\\{$classe}Controller::class, 'historyDetails'])->name('history.details');\n\n            Route::get('/{ {$classeLower} }', [\\{$moduloPai}\\{$classe}Controller::class, 'show'])->name('show');\n        });";
    }
}
