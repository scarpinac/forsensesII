<?php

namespace Tests\Unit\Services;

use App\Services\GeradorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class GeradorServiceTest extends TestCase
{
    use RefreshDatabase;

    private GeradorService $geradorService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->geradorService = new GeradorService();
    }

    protected function tearDown(): void
    {
        // Limpar arquivos criados durante os testes
        $this->cleanupGeneratedFiles();
        parent::tearDown();
    }

    /** @test */
    public function it_can_generate_a_complete_crud()
    {
        $data = [
            'classe' => 'Produto',
            'modulo_pai' => 'Cadastros',
            'campos' => [
                [
                    'nome' => 'nome',
                    'tipo' => 'String',
                    'obrigatorio' => true,
                    'max' => 100,
                    'unique' => true
                ],
                [
                    'nome' => 'descricao',
                    'tipo' => 'Texto Longo',
                    'obrigatorio' => false
                ],
                [
                    'nome' => 'preco',
                    'tipo' => 'Decimal/Valor',
                    'obrigatorio' => true
                ],
                [
                    'nome' => 'ativo',
                    'tipo' => 'Booleano',
                    'obrigatorio' => false
                ]
            ],
            'soft_delete' => true,
            'criar_permissoes' => false,
            'criar_menu' => false
        ];

        $result = $this->geradorService->gerarCadastro($data);

        // Verificar se todos os arquivos foram gerados
        $this->assertArrayHasKey('files', $result);
        $this->assertCount(9, $result['files']); // migration, migration historico, model, model historico, controller, store request, update request, 7 views, observer

        // Verificar se os arquivos existem
        $this->assertTrue(File::exists(app_path("Models/Produto.php")));
        $this->assertTrue(File::exists(app_path("Models/ProdutoHistorico.php")));
        $this->assertTrue(File::exists(app_path("Http/Controllers/Cadastros/ProdutoController.php")));
        $this->assertTrue(File::exists(app_path("Http/Requests/Cadastros/Produto/StoreRequest.php")));
        $this->assertTrue(File::exists(app_path("Http/Requests/Cadastros/Produto/UpdateRequest.php")));
        $this->assertTrue(File::exists(app_path("Observers/ProdutoObserver.php")));

        // Verificar views
        $viewsPath = resource_path("views/cadastros/produto");
        $this->assertTrue(File::exists("{$viewsPath}/index.blade.php"));
        $this->assertTrue(File::exists("{$viewsPath}/create.blade.php"));
        $this->assertTrue(File::exists("{$viewsPath}/edit.blade.php"));
        $this->assertTrue(File::exists("{$viewsPath}/show.blade.php"));
        $this->assertTrue(File::exists("{$viewsPath}/destroy.blade.php"));
        $this->assertTrue(File::exists("{$viewsPath}/history.blade.php"));
        $this->assertTrue(File::exists("{$viewsPath}/form.blade.php"));

        // Verificar se as migrations foram criadas
        $migrationFiles = glob(database_path("migrations/*create_produtos_table.php"));
        $this->assertNotEmpty($migrationFiles);

        $historyMigrationFiles = glob(database_path("migrations/*create_produtos_historico_table.php"));
        $this->assertNotEmpty($historyMigrationFiles);
    }

    /** @test */
    public function it_generates_correct_migration_content()
    {
        $data = [
            'classe' => 'Categoria',
            'modulo_pai' => 'Cadastros',
            'campos' => [
                [
                    'nome' => 'nome',
                    'tipo' => 'String',
                    'obrigatorio' => true,
                    'max' => 50
                ]
            ],
            'soft_delete' => false,
            'criar_permissoes' => false,
            'criar_menu' => false
        ];

        $this->geradorService->gerarCadastro($data);

        $migrationFiles = glob(database_path("migrations/*create_categorias_table.php"));
        $this->assertNotEmpty($migrationFiles);

        $migrationContent = File::get($migrationFiles[0]);
        
        $this->assertStringContainsString('Schema::create(\'categorias\'', $migrationContent);
        $this->assertStringContainsString('$table->string(\'nome\', 50)', $migrationContent);
        $this->assertStringContainsString('$table->timestamps();', $migrationContent);
        $this->assertStringNotContainsString('$table->softDeletes();', $migrationContent);
    }

    /** @test */
    public function it_generates_model_with_correct_relationships()
    {
        $data = [
            'classe' => 'Item',
            'modulo_pai' => 'Cadastros',
            'campos' => [
                [
                    'nome' => 'nome',
                    'tipo' => 'String',
                    'obrigatorio' => true
                ],
                [
                    'nome' => 'categoria_id',
                    'tipo' => 'Select/Relacionamento',
                    'obrigatorio' => true,
                    'relacionamento' => 'Categoria'
                ]
            ],
            'soft_delete' => false,
            'criar_permissoes' => false,
            'criar_menu' => false
        ];

        $this->geradorService->gerarCadastro($data);

        $modelContent = File::get(app_path("Models/Item.php"));
        
        $this->assertStringContainsString('class Item extends Model', $modelContent);
        $this->assertStringContainsString('protected $fillable = [', $modelContent);
        $this->assertStringContainsString('\'nome\'', $modelContent);
        $this->assertStringContainsString('\'categoria_id\'', $modelContent);
        $this->assertStringContainsString('public function categorias(): BelongsTo', $modelContent);
    }

    /** @test */
    public function it_generates_controller_with_correct_permissions()
    {
        $data = [
            'classe' => 'Teste',
            'modulo_pai' => 'Cadastros',
            'campos' => [
                [
                    'nome' => 'nome',
                    'tipo' => 'String',
                    'obrigatorio' => true
                ]
            ],
            'soft_delete' => false,
            'criar_permissoes' => false,
            'criar_menu' => false
        ];

        $this->geradorService->gerarCadastro($data);

        $controllerContent = File::get(app_path("Http/Controllers/Cadastros/TesteController.php"));
        
        $this->assertStringContainsString('class TesteController extends Controller', $controllerContent);
        $this->assertStringContainsString('abort_if (!Auth::user()->canAccess(\'cadastros.teste.index\')', $controllerContent);
        $this->assertStringContainsString('abort_if (!Auth::user()->canAccess(\'cadastros.teste.create\')', $controllerContent);
        $this->assertStringContainsString('abort_if (!Auth::user()->canAccess(\'cadastros.teste.show\')', $controllerContent);
    }

    /** @test */
    public function it_creates_permissions_when_enabled()
    {
        $data = [
            'classe' => 'PermissaoTest',
            'modulo_pai' => 'Cadastros',
            'campos' => [
                [
                    'nome' => 'nome',
                    'tipo' => 'String',
                    'obrigatorio' => true
                ]
            ],
            'soft_delete' => false,
            'criar_permissoes' => true,
            'criar_menu' => false
        ];

        $result = $this->geradorService->gerarCadastro($data);

        $this->assertArrayHasKey('permissoes', $result);
        $this->assertCount(6, $result['permissoes']); // index, create, edit, show, destroy, history

        $expectedPermissions = [
            'cadastros.permissaotest.index',
            'cadastros.permissaotest.create',
            'cadastros.permissaotest.edit',
            'cadastros.permissaotest.show',
            'cadastros.permissaotest.destroy',
            'cadastros.permissaotest.history'
        ];

        foreach ($expectedPermissions as $permission) {
            $this->assertContains($permission, $result['permissoes']);
            $this->assertDatabaseHas('permissao', ['descricao' => $permission]);
        }
    }

    /** @test */
    public function it_generates_validation_rules_correctly()
    {
        $data = [
            'classe' => 'ValidacaoTest',
            'modulo_pai' => 'Cadastros',
            'campos' => [
                [
                    'nome' => 'email',
                    'tipo' => 'String',
                    'obrigatorio' => true,
                    'max' => 255,
                    'unique' => true
                ],
                [
                    'nome' => 'idade',
                    'tipo' => 'Inteiro',
                    'obrigatorio' => true
                ],
                [
                    'nome' => 'valor',
                    'tipo' => 'Decimal/Valor',
                    'obrigatorio' => false
                ]
            ],
            'soft_delete' => false,
            'criar_permissoes' => false,
            'criar_menu' => false
        ];

        $this->geradorService->gerarCadastro($data);

        $storeRequestContent = File::get(app_path("Http/Requests/Cadastros/ValidacaoTest/StoreRequest.php"));
        
        $this->assertStringContainsString('\'email\' => \'required|string|max:255|unique:validacao_tests,email\'', $storeRequestContent);
        $this->assertStringContainsString('\'idade\' => \'required|integer\'', $storeRequestContent);
        $this->assertStringContainsString('\'valor\' => \'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/\'', $storeRequestContent);
    }

    /** @test */
    public function it_handles_soft_delete_correctly()
    {
        $data = [
            'classe' => 'SoftDeleteTest',
            'modulo_pai' => 'Cadastros',
            'campos' => [
                [
                    'nome' => 'nome',
                    'tipo' => 'String',
                    'obrigatorio' => true
                ]
            ],
            'soft_delete' => true,
            'criar_permissoes' => false,
            'criar_menu' => false
        ];

        $this->geradorService->gerarCadastro($data);

        // Verificar migration
        $migrationFiles = glob(database_path("migrations/*create_soft_delete_tests_table.php"));
        $migrationContent = File::get($migrationFiles[0]);
        $this->assertStringContainsString('$table->softDeletes();', $migrationContent);

        // Verificar model
        $modelContent = File::get(app_path("Models/SoftDeleteTest.php"));
        $this->assertStringContainsString('use Illuminate\Database\Eloquent\SoftDeletes;', $modelContent);
        $this->assertStringContainsString('SoftDeletes', $modelContent);
    }

    private function cleanupGeneratedFiles(): void
    {
        $filesToDelete = [
            app_path("Models/Produto.php"),
            app_path("Models/ProdutoHistorico.php"),
            app_path("Models/Categoria.php"),
            app_path("Models/CategoriaHistorico.php"),
            app_path("Models/Item.php"),
            app_path("Models/ItemHistorico.php"),
            app_path("Models/Teste.php"),
            app_path("Models/TesteHistorico.php"),
            app_path("Models/PermissaoTest.php"),
            app_path("Models/PermissaoTestHistorico.php"),
            app_path("Models/ValidacaoTest.php"),
            app_path("Models/ValidacaoTestHistorico.php"),
            app_path("Models/SoftDeleteTest.php"),
            app_path("Models/SoftDeleteTestHistorico.php"),
            app_path("Http/Controllers/Cadastros/ProdutoController.php"),
            app_path("Http/Controllers/Cadastros/CategoriaController.php"),
            app_path("Http/Controllers/Cadastros/ItemController.php"),
            app_path("Http/Controllers/Cadastros/TesteController.php"),
            app_path("Http/Controllers/Cadastros/PermissaoTestController.php"),
            app_path("Http/Controllers/Cadastros/ValidacaoTestController.php"),
            app_path("Http/Controllers/Cadastros/SoftDeleteTestController.php"),
            app_path("Observers/ProdutoObserver.php"),
            app_path("Observers/CategoriaObserver.php"),
            app_path("Observers/ItemObserver.php"),
            app_path("Observers/TesteObserver.php"),
            app_path("Observers/PermissaoTestObserver.php"),
            app_path("Observers/ValidacaoTestObserver.php"),
            app_path("Observers/SoftDeleteTestObserver.php")
        ];

        foreach ($filesToDelete as $file) {
            if (File::exists($file)) {
                File::delete($file);
            }
        }

        // Remover diretórios de requests
        $requestDirs = [
            app_path("Http/Requests/Cadastros/Produto"),
            app_path("Http/Requests/Cadastros/Categoria"),
            app_path("Http/Requests/Cadastros/Item"),
            app_path("Http/Requests/Cadastros/Teste"),
            app_path("Http/Requests/Cadastros/PermissaoTest"),
            app_path("Http/Requests/Cadastros/ValidacaoTest"),
            app_path("Http/Requests/Cadastros/SoftDeleteTest")
        ];

        foreach ($requestDirs as $dir) {
            if (File::exists($dir)) {
                File::deleteDirectory($dir);
            }
        }

        // Remover diretórios de views
        $viewDirs = [
            resource_path("views/cadastros/produto"),
            resource_path("views/cadastros/categoria"),
            resource_path("views/cadastros/item"),
            resource_path("views/cadastros/teste"),
            resource_path("views/cadastros/permissaotest"),
            resource_path("views/cadastros/validacaotest"),
            resource_path("views/cadastros/softdeletetest")
        ];

        foreach ($viewDirs as $dir) {
            if (File::exists($dir)) {
                File::deleteDirectory($dir);
            }
        }

        // Remover migrations de teste
        $testMigrations = array_merge(
            glob(database_path("migrations/*create_produtos_table.php")),
            glob(database_path("migrations/*create_produtos_historico_table.php")),
            glob(database_path("migrations/*create_categorias_table.php")),
            glob(database_path("migrations/*create_categorias_historico_table.php")),
            glob(database_path("migrations/*create_items_table.php")),
            glob(database_path("migrations/*create_items_historico_table.php")),
            glob(database_path("migrations/*create_testes_table.php")),
            glob(database_path("migrations/*create_testes_historico_table.php")),
            glob(database_path("migrations/*create_permissao_tests_table.php")),
            glob(database_path("migrations/*create_permissao_tests_historico_table.php")),
            glob(database_path("migrations/*create_validacao_tests_table.php")),
            glob(database_path("migrations/*create_validacao_tests_historico_table.php")),
            glob(database_path("migrations/*create_soft_delete_tests_table.php")),
            glob(database_path("migrations/*create_soft_delete_tests_historico_table.php"))
        );

        foreach ($testMigrations as $migration) {
            if (File::exists($migration)) {
                File::delete($migration);
            }
        }
    }
}
