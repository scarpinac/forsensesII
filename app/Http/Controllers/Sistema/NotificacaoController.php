<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sistema\Notificacao\StoreRequest;
use App\Http\Requests\Sistema\Notificacao\UpdateRequest;
use App\Models\Notificacao;
use App\Models\NotificacaoUsuario;
use App\Models\NotificacaoHistorico;
use App\Models\Padrao;
use App\Models\PadraoTipo;
use App\Models\Perfil;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class NotificacaoController extends Controller
{
    protected NotificationService $notificacaoService;

    public function __construct(NotificationService $notificacaoService)
    {
        $this->notificacaoService = $notificacaoService;
    }

    /**
     * Listar notificações
     */
    public function index(Request $request): View
    {
        $query = Notificacao::with(['tipoNotificacao', 'leituras.usuario'])
            ->orderBy('created_at', 'desc');

        $notificacoes = $query->paginate();
        return view('sistema.notificacao.index', compact('notificacoes'));
    }

    /**
     * Formulário de criação
     */
    public function create(): View
    {
        $tipos = PadraoTipo::where('padrao_id', '=', Padrao::TipoNotificacao)->get();
        $tiposDestino = PadraoTipo::where('padrao_id', '=', Padrao::EnviarNotificacaoPara)->get();
        $usuarios = User::get();
        $perfis = Perfil::get();
        $notificacao = new Notificacao;

        return view('sistema.notificacao.create', compact('tipos', 'tiposDestino', 'usuarios', 'perfis', 'notificacao'));
    }

    /**
     * Salvar nova notificação
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $dados = $request->only(['titulo', 'mensagem', 'tipoNotificacao_id', 'icone', 'enviar_em', 'enviarNotificacaoPara_id']);

        try {
            // Preparar dados para agendamento
            $dadosAgendamento = [
                'titulo' => $dados['titulo'],
                'mensagem' => $dados['mensagem'],
                'tipoNotificacao_id' => $dados['tipoNotificacao_id'],
                'icone' => $dados['icone'],
                'enviar_em' => $dados['enviar_em'],
//                'enviarNotificacaoPara_id' => $dados['enviarNotificacaoPara_id'],
                'enviado' => false,
            ];

//            // Preparar campo enviado_para baseado no tipo
//            switch ($dados['enviarNotificacaoPara_id']) {
//                case '16': // Usuários Específicos
//                    $dadosAgendamento['enviado_para'] = json_encode($request->usuarios ?? []);
//                    break;
//                case '17': // Perfis Específicos
//                    $dadosAgendamento['enviado_para'] = json_encode($request->perfis ?? []);
//                    break;
//                case '15': // Todos os Usuários
//                default:
//                    $dadosAgendamento['enviado_para'] = null;
//                    break;
//            }

            $this->notificacaoService->agendarNotificacao($dadosAgendamento);

            return redirect()
                ->signedRoute('sistema.notificacao.index')
                ->with('success', 'Notificação agendada com sucesso para ' . \Carbon\Carbon::parse($dados['enviar_em'])->format('d/m/Y H:i') . '!');
        } catch (\Exception $e) {
            Log::error('Erro ao agendar notificação', [
                'error' => $e->getMessage(),
                'dados' => $dados,
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao agendar notificação.');
        }
    }

    /**
     * Detalhes da notificação
     */
    public function show(Notificacao $notificacao): View
    {
        $notificacao->load(['tipoNotificacao']);
        $tipos = PadraoTipo::where('padrao_id', '=', Padrao::TipoNotificacao)->get();
        $tiposDestino = PadraoTipo::where('padrao_id', '=', Padrao::EnviarNotificacaoPara)->get();
        $usuarios = User::get();
        $perfis = Perfil::get();

        $bloquearCampos = true;
        return view('sistema.notificacao.show', compact('notificacao', 'tipos', 'tiposDestino', 'usuarios', 'perfis', 'bloquearCampos'));
    }

    /**
     * Formulário de edição
     */
    public function edit(Notificacao $notificacao): View
    {
        $tipos = PadraoTipo::where('padrao_id', '=', Padrao::TipoNotificacao)->get();
        $tiposDestino = PadraoTipo::where('padrao_id', '=', Padrao::EnviarNotificacaoPara)->get();
        $usuarios = User::get();
        $perfis = Perfil::get();

        return view('sistema.notificacao.edit', compact('notificacao', 'tipos', 'tiposDestino', 'usuarios', 'perfis'));
    }

    /**
     * Atualizar notificação
     */
    public function update(UpdateRequest $request, Notificacao $notificacao): RedirectResponse
    {
        try {
            $notificacao->update($request->all());

            return redirect()
                ->signedRoute('sistema.notificacao.index')
                ->with('success', 'Notificação atualizada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar notificação', [
                'error' => $e->getMessage(),
                'notificacao_id' => $notificacao->id,
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar notificação: ' . $e->getMessage());
        }
    }


    public function destroy(Notificacao $notificacao)
    {
        if (!Auth::user()->canAccess('sistema.notificacao.destroy')) {
            abort(403);
        }
        $tipos = PadraoTipo::where('padrao_id', '=', Padrao::TipoNotificacao)->get();
        $tiposDestino = PadraoTipo::where('padrao_id', '=', Padrao::EnviarNotificacaoPara)->get();
        $usuarios = User::get();
        $perfis = Perfil::get();
        $bloquearCampos = true;

        return view('sistema.notificacao.destroy', compact('notificacao', 'bloquearCampos', 'tipos', 'tiposDestino', 'usuarios', 'perfis'));
    }

    /**
     * Excluir notificação
     */
    public function delete(Notificacao $notificacao): RedirectResponse
    {
        try {
            $notificacao->delete();

            return redirect()
                ->signedRoute('sistema.notificacao.index')
                ->with('success', 'Notificação excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir notificação', [
                'error' => $e->getMessage(),
                'notificacao_id' => $notificacao->id,
            ]);

            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir notificação: ' . $e->getMessage());
        }
    }

    /**
     * API: Obter notificações do usuário
     */
    public function getNotificacoesUsuario(Request $request): JsonResponse
    {
        $user = Auth::user();
        $notificacoes = $this->notificacaoService->getUnreadNotifications($user);

        return response()->json([
            'notifications' => $notificacoes,
            'unread_count' => $this->notificacaoService->getUnreadCount($user),
        ]);
    }

    /**
     * API: Marcar notificação como lida
     */
    public function marcarComoLida(Request $request): JsonResponse
    {
        Log::info('marcarComoLida chamado', [
            'notification_id' => $request->notification_id,
            'user_authenticated' => Auth::check(),
            'user_id' => Auth::id(),
        ]);

        $request->validate([
            'notification_id' => 'required|exists:notificacao,id',
        ]);

        $user = Auth::user();
        $notificacao = Notificacao::findOrFail($request->notification_id);

        Log::info('Verificando permissão', [
            'user_id' => $user->id,
            'notification_id' => $notificacao->id,
            'notification_tipo' => $notificacao->tipoNotificacao_id,
        ]);

        try {
            $notificacao = Notificacao::findOrFail($request->notification_id);
            $leitura = $this->notificacaoService->markAsRead($notificacao, $user);

            Log::info('Notificação marcada como lida', [
                'leitura_id' => $leitura->id,
                'lida' => $leitura->lida,
            ]);

            return response()->json([
                'success' => true,
                'lida_em' => $leitura->lida_em,
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao marcar notificação como lida', [
                'error' => $e->getMessage(),
                'notification_id' => $request->notification_id,
                'user_id' => $user->id,
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Marcar todas como lidas
     */
    public function marcarTodasComoLidas(): JsonResponse
    {
        $user = Auth::user();
        $contador = $this->notificacaoService->markAllAsRead($user);

        return response()->json([
            'success' => true,
            'contador' => $contador,
        ]);
    }

    /**
     * Histórico da notificação
     */
    public function historico(Notificacao $notificacao): View
    {
        // TODO: Implementar método getHistoricoNotificacao no NotificationService
        $historico = collect(); // Temporariamente vazio

        return view('sistema.notificacao.historico', compact('notificacao', 'historico'));
    }

    public function history(Notificacao $notificacao)
    {
        abort_if (!Auth::user()->canAccess('sistema.notificacao.history'), 403, 'Acesso não autorizado');
        $tipos = PadraoTipo::where('padrao_id', '=', Padrao::TipoNotificacao)->get();
        $tiposDestino = PadraoTipo::where('padrao_id', '=', Padrao::EnviarNotificacaoPara)->get();
        $usuarios = User::get();
        $perfis = Perfil::get();

        $bloquearCampos = true;
        return view('sistema.notificacao.history', compact('notificacao', 'tipos', 'tiposDestino', 'usuarios', 'perfis', 'bloquearCampos'));
    }

    /**
     * Show the details of a specific history record.
     */
    public function historyDetails(Notificacao $notificacao, $historico)
    {

        if (!Auth::user()->canAccess('sistema.notificacao.history')) {
            abort(403);
        }

        $historico = $notificacao->historicos()->with(['user', 'tipoAlteracao'])->findOrFail($historico);

        $dadosAnteriores = $historico->dados_anteriores;
        $dadosNovos = $historico->dados_novos;

        // Tratar campos especiais
        if ($dadosAnteriores) {
            foreach ($dadosAnteriores as $key => $value) {
                // Formatar timestamps
                if (in_array($key, ['created_at', 'updated_at', 'deleted_at']) && $value) {
                    try {
                        $dadosAnteriores[$key] = \Carbon\Carbon::parse($value)->format('d/m/Y H:i:s');
                    } catch (\Exception $e) {
                        // Mantém o valor original se não conseguir parsear
                    }
                }
                
                // Tratar campo tipoNotificacao_id
                if ($key === 'tipoNotificacao_id' && $value) {
                    $tipo = \App\Models\PadraoTipo::find($value);
                    $dadosAnteriores[$key] = $tipo ? $tipo->descricao : $value;
                }
                
                // Tratar campo enviarNotificacaoPara_id
                if ($key === 'enviarNotificacaoPara_id' && $value) {
                    $tipo = \App\Models\PadraoTipo::find($value);
                    $dadosAnteriores[$key] = $tipo ? $tipo->descricao : $value;
                }
                
                // Tratar campo enviado_para (JSON)
                if ($key === 'enviado_para' && $value) {
                    if (is_string($value)) {
                        $decoded = json_decode($value, true);
                        $dadosAnteriores[$key] = $decoded ? json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $value;
                    }
                }
                
                // Tratar campo enviado (boolean)
                if ($key === 'enviado') {
                    $dadosAnteriores[$key] = $value ? __('labels.notification.yes') : __('labels.notification.no');
                }
            }
        }

        if ($dadosNovos) {
            foreach ($dadosNovos as $key => $value) {
                // Formatar timestamps
                if (in_array($key, ['created_at', 'updated_at', 'deleted_at']) && $value) {
                    try {
                        $dadosNovos[$key] = \Carbon\Carbon::parse($value)->format('d/m/Y H:i:s');
                    } catch (\Exception $e) {
                        // Mantém o valor original se não conseguir parsear
                    }
                }
                
                // Tratar campo tipoNotificacao_id
                if ($key === 'tipoNotificacao_id' && $value) {
                    $tipo = \App\Models\PadraoTipo::find($value);
                    $dadosNovos[$key] = $tipo ? $tipo->descricao : $value;
                }
                
                // Tratar campo enviarNotificacaoPara_id
                if ($key === 'enviarNotificacaoPara_id' && $value) {
                    $tipo = \App\Models\PadraoTipo::find($value);
                    $dadosNovos[$key] = $tipo ? $tipo->descricao : $value;
                }
                
                // Tratar campo enviado_para (JSON)
                if ($key === 'enviado_para' && $value) {
                    if (is_string($value)) {
                        $decoded = json_decode($value, true);
                        $dadosNovos[$key] = $decoded ? json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $value;
                    }
                }
                
                // Tratar campo enviado (boolean)
                if ($key === 'enviado') {
                    $dadosNovos[$key] = $value ? __('labels.notification.yes') : __('labels.notification.no');
                }
            }
        }

        // Mapeamento de campos para exibição amigável baseado no idioma atual
        $camposTabela = [
            // Campos principais da tabela
            'id' => __('labels.notification.history.fields.id'),
            'titulo' => __('labels.notification.title'),
            'mensagem' => __('labels.notification.message'),
            'tipoNotificacao_id' => __('labels.notification.notification_type'),
            'icone' => __('labels.notification.icon'),
            'enviar_em' => __('labels.notification.sendAt'),
            'enviado' => __('labels.notification.sended'),
            'enviarNotificacaoPara_id' => __('labels.notification.destiny'),
            'enviado_para' => __('labels.notification.sendTo'),
            
            // Campos de timestamp
            'created_at' => __('labels.notification.history.fields.created_at'),
            'updated_at' => __('labels.notification.history.fields.updated_at'),
            'deleted_at' => __('labels.notification.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }
}
