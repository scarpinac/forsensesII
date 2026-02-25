<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
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
        Log::info('GeradorService::gerarCadastro iniciado com dados:', $data);

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

        Log::info('Iniciando geração de arquivos...');

        // Gerar arquivos
        Log::info('Gerando migration...');
        $migrationFiles = $this->gerarMigration();
        Log::info('Migration gerada: ' . json_encode($migrationFiles));

        // Gerar migration de histórico
        Log::info('Gerando migration de histórico...');
        $historyMigrationFiles = $this->gerarMigrationHistorico();
        Log::info('Migration de histórico gerada: ' . json_encode($historyMigrationFiles));
        $migrationFiles = array_merge($migrationFiles, $historyMigrationFiles);

        Log::info('Gerando model...');
        $modelFiles = $this->gerarModel();
        Log::info('Model gerado: ' . json_encode($modelFiles));

        Log::info('Gerando controller...');
        $controllerFiles = $this->gerarController();
        Log::info('Controller gerado: ' . json_encode($controllerFiles));

        Log::info('Gerando requests...');
        $requestFiles = $this->gerarRequests();
        Log::info('Requests gerados: ' . json_encode($requestFiles));

        Log::info('Gerando views...');
        $viewFiles = $this->gerarViews();
        Log::info('Views geradas: ' . json_encode($viewFiles));

        Log::info('Gerando observer...');
        $observerFiles = $this->gerarObserver();
        Log::info('Observer gerado: ' . json_encode($observerFiles));

        $resultado['files'] = array_merge(
            $migrationFiles,
            $modelFiles,
            $controllerFiles,
            $requestFiles,
            $viewFiles,
            $observerFiles
        );

        Log::info('Arquivos gerados:', $resultado['files']);

        // Executar migration automaticamente (comentado para teste)
        // \Illuminate\Support\Facades\Artisan::call('migrate', [
        //     '--force' => true
        // ]);

        // Gerar permissões
        if ($data['criar_permissoes']) {
            $resultado['permissoes'] = $this->gerarPermissoes();
        }

        // Gerar menu
        if ($data['criar_menu']) {
            $resultado['menu'] = $this->gerarMenu();
        }

        // Registrar rotas
//        $this->registrarRotas();

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
        $relacionamento = $campo['relacionamento'] ?? null;

        $line = "            \$table->";

        // Mapear tipos
        switch ($tipo) {
            case 'String':
                $line .= $max ? "string('{$nome}', {$max})" : "string('{$nome}')";
                break;
            case 'Inteiro':
                $line .= "integer('{$nome}')";
                break;
            case 'Decimal/Valor':
                $line .= "decimal('{$nome}', 10, 2)";
                break;
            case 'Data':
                $line .= "date('{$nome}')";
                break;
            case 'Booleano':
                $line .= "boolean('{$nome}')";
                break;
            case 'Texto Longo':
                $line .= "text('{$nome}')";
                break;
            case 'Arquivo':
                $line .= "string('{$nome}')";
                break;
            case 'Select/Relacionamento':
                $line .= "foreignId('{$nome}')->nullable()";
                if ($relacionamento && !empty($relacionamento)) {
                    $tabelaRelacionamento = Str::snake(Str::plural($relacionamento));
                    $line .= "->constrained('{$tabelaRelacionamento}')";
                }
                break;
        }

        if ($obrigatorio && $tipo !== 'Select/Relacionamento') {
            $line = str_replace(')', ')->notNullable()', $line);
        }

        if ($unique) {
            $line .= "->unique()";
        }

        $line .= ";\n";

        return $line;
    }

    private function gerarMigrationHistorico(): array
    {
        $timestamp = date('Y_m_d_His', strtotime('+1 second')); // Adicionar 1 segundo para evitar conflito
        $tabelaHistorico = Str::snake($this->tabela) . '_historico';
        $filename = "{$timestamp}_create_{$tabelaHistorico}_table.php";
        $path = database_path("migrations/{$filename}");

        $content = $this->getTemplateMigrationHistorico($tabelaHistorico, $this->tabela);

        File::put($path, $content);
        return ["Migration Histórico: {$filename}"];
    }

    private function getTemplateMigrationHistorico($tabelaHistorico, $tabelaPrincipal): string
    {
        $classePrincipal = $this->classe;

        return "<?php\n\nuse Illuminate\\Database\\Migrations\\Migration;\nuse Illuminate\\Database\\Schema\\Blueprint;\nuse Illuminate\\Support\\Facades\\Schema;\n\nreturn new class extends Migration\n{\n    /**\n     * Run the migrations.\n     */\n    public function up(): void\n    {\n        Schema::create('{$tabelaHistorico}', function (Blueprint \$table) {\n            \$table->id();\n            \$table->foreignId('user_id')->constrained('users');\n            \$table->foreignId('{$this->tabela}_id')->constrained('{$tabelaPrincipal}');\n            \$table->text('dados_anteriores')->nullable();\n            \$table->text('dados_novos')->nullable();\n            \$table->foreignId('tipoAlteracao_id')->constrained('padrao_tipo');\n            \$table->timestamps();\n            \$table->softDeletes();\n        });\n    }\n\n    /**\n     * Reverse the migrations.\n     */\n    public function down(): void\n    {\n        Schema::dropIfExists('{$tabelaHistorico}');\n    }\n};";
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

        // Gerar Model de Histórico
        $historyModelPath = app_path("Models/{$this->classe}Historico.php");
        $historyContent = $this->getTemplateModelHistorico($this->classe, $this->tabela);
        File::put($historyModelPath, $historyContent);

        return [
            "Model: app/Models/{$this->classe}.php",
            "Model Histórico: app/Models/{$this->classe}Historico.php"
        ];
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
        Log::info('gerarViews iniciado');
        $files = [];
        $dir = resource_path("views/" . strtolower($this->moduloPai) . "/" . Str::kebab($this->classe));

        Log::info('Diretorio das views: ' . $dir);

        if (!File::exists($dir)) {
            Log::info('Criando diretório: ' . $dir);
            File::makeDirectory($dir, 0755, true);
        }

        $views = ['index', 'create', 'edit', 'show', 'destroy', 'history', 'form'];
        Log::info('Views para gerar: ' . json_encode($views));

        foreach ($views as $view) {
            Log::info('Gerando view: ' . $view);
            $path = "{$dir}/{$view}.blade.php";
            Log::info('Caminho da view: ' . $path);

            try {
                $content = $this->getTemplateView($view, $this->classe, $this->moduloPai, $this->data);
                Log::info('Template obtido para view: ' . $view);

                File::put($path, $content);
                Log::info('Arquivo criado: ' . $path);

                $files[] = "View: resources/views/{$this->moduloPai}/" . Str::kebab($this->classe) . "/{$view}.blade.php";
            } catch (\Exception $e) {
                Log::error('Erro ao gerar view ' . $view . ': ' . $e->getMessage());
                throw $e;
            }
        }

        Log::info('gerarViews finalizado');
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

        $menuPai = Menu::where('descricao', '=', $this->moduloPai)->first();

        $menuData = [
            'descricao' => $this->classe, // Usar exatamente o nome da classe
            'icone' => 'fas fa-database',
            'rota' => $permissaoIndex,
            'menuPai_id' => $menuPai ? $menuPai->id : null,
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

        // Encontrar o último }); e adicionar as novas rotas antes
        $lastBracePos = strrpos($routeContent, "});");
        if ($lastBracePos !== false) {
            $routeContent = substr($routeContent, 0, $lastBracePos) . $newRoute . "\n    });" . substr($routeContent, $lastBracePos + 2);
        } else {
            $routeContent .= $newRoute;
        }

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

        $timestamps = "\n            \$table->timestamps();";
        $softDeletes = $data['soft_delete'] ? "\n            \$table->softDeletes();" : "";

        return "<?php\n\nuse Illuminate\\Database\\Migrations\\Migration;\nuse Illuminate\\Database\\Schema\\Blueprint;\nuse Illuminate\\Support\\Facades\\Schema;\n\nreturn new class extends Migration\n{\n    /**\n     * Run the migrations.\n     */\n    public function up(): void\n    {\n        Schema::create('{$tabela}', function (Blueprint \$table) {\n            \$table->id();{$campos}{$timestamps}{$softDeletes}\n        });\n    }\n\n    /**\n     * Reverse the migrations.\n     */\n    public function down(): void\n    {\n        Schema::dropIfExists('{$tabela}');\n    }\n};";
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
            $obrigatorio = $campo['obrigatorio'] ?? false;
            $max = $campo['max'] ?? null;
            $unique = $campo['unique'] ?? false;
            $relacionamento = $campo['relacionamento'] ?? null;

            // Gerar mensagens apenas para as regras que realmente são usadas
            if ($obrigatorio) {
                $messages[] = "            '{$nome}.required' => __('messages.{$classeLower}.validation.{$nome}.required'),";
            }

            // Mapear tipos para mensagens
            switch ($tipo) {
                case 'string':
                    $messages[] = "            '{$nome}.string' => __('messages.{$classeLower}.validation.{$nome}.string'),";
                    if ($max) $messages[] = "            '{$nome}.max' => __('messages.{$classeLower}.validation.{$nome}.max'),";
                    break;
                case 'integer':
                    $messages[] = "            '{$nome}.integer' => __('messages.{$classeLower}.validation.{$nome}.integer'),";
                    break;
                case 'double':
                    $messages[] = "            '{$nome}.numeric' => __('messages.{$classeLower}.validation.{$nome}.numeric'),";
                    $messages[] = "            '{$nome}.regex' => __('messages.{$classeLower}.validation.{$nome}.regex'),";
                    break;
                case 'date':
                    $messages[] = "            '{$nome}.date' => __('messages.{$classeLower}.validation.{$nome}.date'),";
                    break;
                case 'boolean':
                    $messages[] = "            '{$nome}.boolean' => __('messages.{$classeLower}.validation.{$nome}.boolean'),";
                    break;
                case 'text':
                    $messages[] = "            '{$nome}.string' => __('messages.{$classeLower}.validation.{$nome}.string'),";
                    break;
                case 'file':
                    $messages[] = "            '{$nome}.file' => __('messages.{$classeLower}.validation.{$nome}.file'),";
                    $messages[] = "            '{$nome}.max' => __('messages.{$classeLower}.validation.{$nome}.max'),";
                    break;
                case 'select':
                    if ($relacionamento && !empty($relacionamento)) {
                        $messages[] = "            '{$nome}.exists' => __('messages.{$classeLower}.validation.{$nome}.exists'),";
                    }
                    break;
            }

            if ($unique) {
                $messages[] = "            '{$nome}.unique' => __('messages.{$classeLower}.validation.{$nome}.unique'),";
            }
        }

        return implode("\n", array_unique($messages));
    }

    private function getTemplateView($view, $classe, $moduloPai, $data)
    {
        $classeLower = strtolower($classe);
        $moduloLower = strtolower($moduloPai);
        $classeKebab = Str::kebab($classe);

        // Usar templates da pasta template/exemplo como base
        $templatePath = resource_path("views/template/exemplo/{$view}.blade.php");

        if (File::exists($templatePath)) {
            // Ler o template de exemplo
            $template = File::get($templatePath);

            // Substituir as variáveis
            $replacements = [
                'template.exemplo' => "{$moduloLower}.{$classeLower}",
                'exemplo' => $classeLower,
                'exemplos' => $classeLower . 's',
                'Exemplo' => $classe,
                'labels.exemplo' => "labels.{$classeLower}",
                'exempo' => $classeLower, // Fix para history.blade.php
            ];

            return str_replace(array_keys($replacements), array_values($replacements), $template);
        }

        // Fallback para templates genéricos se não existir
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

        // Ler o template como string
        $templatePath = resource_path('views/templates/index.blade.php');
        $template = File::get($templatePath);

        // Substituir as variáveis
        $replacements = [
            '{{ $title }}' => $title,
            '{{ $variable }}' => $variable,
            '{{ $variablePlural }}' => $variablePlural,
            '{{ $permissions.create }}' => $permissions['create'],
            '{{ $permissions.show }}' => $permissions['show'],
            '{{ $permissions.edit }}' => $permissions['edit'],
            '{{ $permissions.destroy }}' => $permissions['destroy'],
            '{{ $routes.create }}' => $routes['create'],
            '{{ $routes.show }}' => $routes['show'],
            '{{ $routes.edit }}' => $routes['edit'],
            '{{ $routes.destroy }}' => $routes['destroy'],
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }

    private function getTemplateCreateView($classe, $classeLower, $moduloLower, $classeKebab)
    {
        $title = $classe;
        $formView = "{$moduloLower}.{$classeKebab}.form";

        // Ler o template como string
        $templatePath = resource_path('views/templates/create.blade.php');
        $template = File::get($templatePath);

        // Substituir as variáveis
        $replacements = [
            '{{ $title }}' => $title,
            '{{ $formView }}' => $formView,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }

    private function getTemplateEditView($classe, $classeLower, $moduloLower, $classeKebab)
    {
        $title = $classe;
        $formView = "{$moduloLower}.{$classeKebab}.form";

        // Ler o template como string
        $templatePath = resource_path('views/templates/edit.blade.php');
        $template = File::get($templatePath);

        // Substituir as variáveis
        $replacements = [
            '{{ $title }}' => $title,
            '{{ $formView }}' => $formView,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }

    private function getTemplateFormView($classe, $classeLower, $moduloLower, $data)
    {
        // Gerar HTML dos campos
        $fieldsHtml = $this->gerarCamposForm($data);

        // Usar o template dinâmico de exemplo
        $templatePath = resource_path('views/template/exemplo/form.blade.php');
        $template = File::get($templatePath);

        // Substituir as variáveis
        $replacements = [
            '{{ $fieldsHtml }}' => $fieldsHtml,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }

    private function gerarCamposForm($data)
    {
        $campos = [];
        $totalCampos = count($data['campos']);
        $colSize = $totalCampos <= 2 ? 12 : ($totalCampos <= 4 ? 6 : 4);

        foreach ($data['campos'] as $index => $campo) {
            $nome = $campo['nome'];
            $tipo = $campo['tipo'];
            $obrigatorio = $campo['obrigatorio'] ?? false;
            $max = $campo['max'] ?? null;
            $unique = $campo['unique'] ?? false;
            $relacionamento = $campo['relacionamento'] ?? null;

            $required = $obrigatorio ? 'required' : '';
            $label = ucfirst($nome);
            $disabled = isset($bloquearCampos) && $bloquearCampos ? 'disabled' : '';

            // Abrir nova linha a cada 2 campos (para col-md-6) ou 3 campos (para col-md-4)
            if ($index % 2 == 0) {
                $campos[] = '<div class="row">';
            }

            $html = "        <div class=\"form-group col-md-{$colSize}\">\n";
            $html .= "            <label for=\"{$nome}\">{$label}</label>\n";

            switch ($tipo) {
                case 'string':
                case 'integer':
                case 'double':
                    $inputType = ($tipo == 'double') ? 'number' : 'text';
                    $step = ($tipo == 'double') ? 'step="0.01"' : '';
                    $html .= "            <input type=\"{$inputType}\" {$step} class=\"form-control\" name=\"{$nome}\" id=\"{$nome}\" value=\"{{ old('{$nome}', \${$nome} ?? null) }}\" {$disabled} {$required}>\n";
                    break;
                case 'date':
                    $html .= "            <input type=\"date\" class=\"form-control\" name=\"{$nome}\" id=\"{$nome}\" value=\"{{ old('{$nome}', \${$nome} ?? null) }}\" {$disabled} {$required}>\n";
                    break;
                case 'boolean':
                    $checked = "{{ old('{$nome}', \${$nome} ?? false) ? 'checked' : '' }}";
                    $html .= "            <div class=\"form-check\">\n";
                    $html .= "                <input type=\"checkbox\" class=\"form-check-input\" name=\"{$nome}\" id=\"{$nome}\" {$disabled} {$checked}>\n";
                    $html .= "                <label class=\"form-check-label\" for=\"{$nome}\">{$label}</label>\n";
                    $html .= "            </div>\n";
                    break;
                case 'text':
                    $html .= "            <textarea class=\"form-control\" name=\"{$nome}\" id=\"{$nome}\" rows=\"3\" {$disabled} {$required}>{{ old('{$nome}', \${$nome} ?? null) }}</textarea>\n";
                    break;
                case 'file':
                    $html .= "            <input type=\"file\" class=\"form-control\" name=\"{$nome}\" id=\"{$nome}\" {$disabled} {$required}>\n";
                    break;
                case 'select':
                    $html .= "            <select class=\"form-control\" name=\"{$nome}\" id=\"{$nome}\" {$disabled} {$required}>\n";
                    $html .= "                <option value=\"\">Selecione...</option>\n";
                    if ($relacionamento && !empty($relacionamento)) {
                        // TODO: Carregar opções do relacionamento
                        $html .= "                    {{-- TODO: Carregar \${$relacionamento}s do relacionamento --}}\n";
                    }
                    $html .= "            </select>\n";
                    break;
            }

            $html .= "            @error('{$nome}')\n";
            $html .= "                <div class=\"invalid-feedback font-weight-bold\" role=\"alert\">\n";
            $html .= "                    {{ \$message }}\n";
            $html .= "                </div>\n";
            $html .= "            @enderror\n";
            $html .= "        </div>\n";

            $campos[] = $html;

            // Fechar linha a cada 2 campos ou no último campo
            if ($index % 2 == 1 || $index == $totalCampos - 1) {
                $campos[] = '    </div>';
            }
        }

        return implode("\n", $campos);
    }

    private function getTemplateGenericView($view, $classe, $classeLower, $moduloLower, $classeKebab)
    {
        $title = $classe;
        $action = ucfirst($view);
        $variable = $classeLower;
        $variablePlural = $classeLower . 's';

        // Verificar se existe template específico
        $templatePath = resource_path("views/templates/{$view}.blade.php");

        if (File::exists($templatePath)) {
            // Ler o template como string
            $template = File::get($templatePath);

            // Substituir as variáveis
            $replacements = [
                '{{ $title }}' => $title,
                '{{ $action }}' => $action,
                '{{ $variable }}' => $variable,
                '{{ $variablePlural }}' => $variablePlural,
            ];

            return str_replace(array_keys($replacements), array_values($replacements), $template);
        } else {
            // Se não existir, criar um template básico
            return "@extends('adminlte::page')

@section('title', '{$action} {$title}')

@section('content_header')
    <h1 class='m-0'>{$action} {$title}</h1>
@endsection

@section('content')
<div class='container-fluid'>
    <div class='row'>
        <div class='col-12'>
            <div class='card'>
                <div class='card-header'>
                    <h3 class='card-title'>{$action} {$title}</h3>
                </div>
                <div class='card-body'>
                    <p>View {$view} para {$title} - Em construção</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection";
        }
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

    private function getTemplateModelHistorico($classe, $tabela): string
    {
        $tabelaHistorico = $tabela . '_historico';
        $classeLower = strtolower($classe);

        return "<?php\n\nnamespace App\\Models;\n\nuse Illuminate\\Database\\Eloquent\\Factories\\HasFactory;\nuse Illuminate\\Database\\Eloquent\\Model;\nuse Illuminate\\Database\\Eloquent\\SoftDeletes;\n\nclass {$classe}Historico extends Model\n{\n    use HasFactory, SoftDeletes;\n\n    protected \$table = '{$tabelaHistorico}';\n\n    protected \$fillable = [\n        'user_id',\n        '{$classeLower}_id',\n        'dados_anteriores',\n        'dados_novos',\n        'tipoAlteracao_id'\n    ];\n\n    protected \$casts = [\n        'dados_anteriores' => 'array',\n        'dados_novos' => 'array',\n    ];\n\n    public function user()\n    {\n        return \$this->belongsTo(User::class);\n    }\n\n    public function {$classeLower}()\n    {\n        return \$this->belongsTo({$classe}::class);\n    }\n\n    public function tipoAlteracao()\n    {\n        return \$this->belongsTo(PadraoTipo::class, 'tipoAlteracao_id');\n    }\n}";
    }
}
