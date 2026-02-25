<?php

namespace App\Services\Generators;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ControllerGenerator
{
    private string $classe;
    private string $moduloPai;
    private string $tabela;
    private array $data;
    private string $namespace;

    public function __construct(string $classe, string $moduloPai, array $data)
    {
        $this->classe = $classe;
        $this->moduloPai = $moduloPai;
        $this->tabela = Str::snake($classe);
        $this->data = $data;
        $this->namespace = 'App\\Http\\Controllers\\' . $moduloPai;
    }

    public function generate(): string
    {
        $path = app_path("Http/Controllers/{$this->moduloPai}/{$this->classe}Controller.php");

        // Criar diretório se não existir
        $dir = dirname($path);
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $content = $this->getControllerTemplate();

        File::put($path, $content);
        return "Controller: app/Http/Controllers/{$this->moduloPai}/{$this->classe}Controller.php";
    }

    private function getControllerTemplate(): string
    {
        $moduloLower = strtolower($this->moduloPai);
        $classeLower = strtolower($this->classe);

        return "<?php\n\nnamespace {$this->namespace};\n\nuse App\\Http\\Controllers\\Controller;\nuse App\\Models\\{$this->classe};\nuse App\\Http\\Requests\\{$this->moduloPai}\\{$this->classe}\\StoreRequest;\nuse App\\Http\\Requests\\{$this->moduloPai}\\{$this->classe}\\UpdateRequest;\nuse Illuminate\\Http\\Request;\nuse Illuminate\\Support\\Facades\\Auth;\n\nclass {$this->classe}Controller extends Controller\n{\n    /**\n     * Display a listing of the resource.\n     */\n    public function index()\n    {\n        abort_if (!Auth::user()->canAccess('{$moduloLower}.{$classeLower}.index'), 403, 'Acesso não autorizado');\n\n        \${$classeLower}s = {$this->classe}::paginate();\n        return view('{$moduloLower}." . Str::kebab($this->classe) . ".index', compact('{$classeLower}s'));\n    }\n\n    /**\n     * Show the form for creating a new resource.\n     */\n    public function create()\n    {\n        abort_if (!Auth::user()->canAccess('{$moduloLower}.{$classeLower}.create'), 403, 'Acesso não autorizado');\n\n        return view('{$moduloLower}." . Str::kebab($this->classe) . ".create');\n    }\n\n    /**\n     * Store a newly created resource in storage.\n     */\n    public function store(StoreRequest \$request)\n    {\n        \${$classeLower} = {$this->classe}::create(\$request->validated());\n\n        if(\${$classeLower}) {\n            return redirect()->signedRoute('{$moduloLower}.{$classeLower}.index')->with('success', __('labels.{$classeLower}.success.created'));\n        }\n        return redirect()->signedRoute('{$moduloLower}.{$classeLower}.index')->with('error', __('labels.{$classeLower}.error.not_saved'));\n    }\n\n    /**\n     * Display the specified resource.\n     */\n    public function show({$this->classe} \${$classeLower})\n    {\n        abort_if (!Auth::user()->canAccess('{$moduloLower}.{$classeLower}.show'), 403, 'Acesso não autorizado');\n\n        \$bloquearCampos = true;\n        return view('{$moduloLower}." . Str::kebab($this->classe) . ".show', compact('{$classeLower}', 'bloquearCampos'));\n    }\n\n    /**\n     * Show the form for editing the specified resource.\n     */\n    public function edit({$this->classe} \${$classeLower})\n    {\n        abort_if (!Auth::user()->canAccess('{$moduloLower}.{$classeLower}.edit'), 403, 'Acesso não autorizado');\n\n        return view('{$moduloLower}." . Str::kebab($this->classe) . ".edit', compact('{$classeLower}'));\n    }\n\n    /**\n     * Update the specified resource in storage.\n     */\n    public function update(UpdateRequest \$request, {$this->classe} \${$classeLower})\n    {\n        if(\${$classeLower}->update(\$request->validated())) {\n            return redirect()->signedRoute('{$moduloLower}.{$classeLower}.index')->with('success', __('labels.{$classeLower}.success.updated'));\n        }\n        return redirect()->signedRoute('{$moduloLower}.{$classeLower}.index')->with('error', __('labels.{$classeLower}.error.not_updated'));\n    }\n\n    /**\n     * Remove the specified resource from storage.\n     */\n    public function destroy({$this->classe} \${$classeLower})\n    {\n        abort_if (!Auth::user()->canAccess('{$moduloLower}.{$classeLower}.destroy'), 403, 'Acesso não autorizado');\n\n        return view('{$moduloLower}." . Str::kebab($this->classe) . ".destroy', compact('{$classeLower}'));\n    }\n\n    /**\n     * Delete the specified resource from storage.\n     */\n    public function delete({$this->classe} \${$classeLower})\n    {\n        abort_if (!Auth::user()->canAccess('{$moduloLower}.{$classeLower}.destroy'), 403, 'Acesso não autorizado');\n\n        if(\${$classeLower}->delete()) {\n            return redirect()->signedRoute('{$moduloLower}.{$classeLower}.index')->with('success', __('labels.{$classeLower}.success.deleted'));\n        }\n        return redirect()->signedRoute('{$moduloLower}.{$classeLower}.index')->with('error', __('labels.{$classeLower}.error.not_deleted'));\n    }\n\n    /**\n     * Display the history of the specified resource.\n     */\n    public function history({$this->classe} \${$classeLower})\n    {\n        abort_if (!Auth::user()->canAccess('{$moduloLower}.{$classeLower}.history'), 403, 'Acesso não autorizado');\n\n        \$historicos = \${$classeLower}->historicos()->paginate();\n        return view('{$moduloLower}." . Str::kebab($this->classe) . ".history', compact('{$classeLower}', 'historicos'));\n    }\n\n    /**\n     * Display the details of a specific history record.\n     */\n    public function historyDetails({$this->classe} \${$classeLower}, \$historico)\n    {\n        abort_if (!Auth::user()->canAccess('{$moduloLower}.{$classeLower}.history'), 403, 'Acesso não autorizado');\n\n        \$historico = \${$classeLower}->historicos()->findOrFail(\$historico);\n        return view('{$moduloLower}." . Str::kebab($this->classe) . ".history_details', compact('{$classeLower}', 'historico'));\n    }\n}";
    }
}
