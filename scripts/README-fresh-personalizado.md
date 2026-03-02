# Fresh Personalizado - ForSenses II

Este script/comando permite fazer um fresh personalizado do banco de dados com controle preciso sobre a ordem de execução das migrations e seeders.

## 🎯 **Objetivo**

Executar o processo de fresh na seguinte ordem:
1. Limpar completamente o banco
2. Rodar migrations até `2026_02_17_143000_add_performance_indexes.php`
3. Executar todos os seeders
4. Continuar com as migrations restantes
5. Otimizar cache

## 📋 **Formas de Uso**

### **Opção 1: Script Bash (Recomendado)**

```bash
# Executar o script
./scripts/fresh-personalizado.sh
```

### **Opção 2: Comando Artisan**

```bash
# Executar com confirmação
php artisan fresh:personalizado

# Executar sem confirmação (automático)
php artisan fresh:personalizado --force

# Especificar migration limite diferente
php artisan fresh:personalizado --migration=2026_02_15_120000_outra_migration.php
```

## ⚠️ **Atenção**

- **PERDA DE DADOS**: Este processo irá **APAGAR TODOS OS DADOS** do banco
- Faça backup antes de executar em produção
- Use apenas em ambiente de desenvolvimento

## 🔧 **O que o script faz?**

### **Etapas do Processo:**

1. **Limpeza Completa**
   - `php artisan db:wipe --force`
   - Remove todas as tabelas

2. **Migrations Iniciais**
   - Executa **TODAS** as migrations em ordem até `2026_02_17_143000_add_performance_indexes.php`
   - Cada migration é executada individualmente para garantir controle preciso
   - Inclui tabelas básicas do sistema

3. **Seeders**
   - `php artisan db:seed --force`
   - Popula dados iniciais (usuários, permissões, tipos, etc.)

4. **Migrations Restantes**
   - `php artisan migrate --force`
   - Continua com as migrations de funcionalidades específicas

5. **Otimização**
   - `php artisan config:cache`
   - `php artisan route:cache`
   - `php artisan view:cache`

6. **Verificação**
   - `php artisan migrate:status`
   - Exibe status final das migrations

## 📁 **Arquivos Criados**

- `scripts/fresh-personalizado.sh` - Script bash com interface amigável
- `app/Console/Commands/FreshPersonalizado.php` - Comando Artisan interno

## 🚀 **Exemplo de Saída**

```
🚀 Iniciando processo de Fresh Personalizado...

📍 [1/6] - Limpando banco de dados completamente...
✅ Banco de dados limpo com sucesso!

📍 [2/6] - Rodando migrations iniciais (até 2026_02_17_143000_add_performance_indexes.php)...
   Executando: 2014_10_12_000000_create_users_table.php
   Executando: 2014_10_12_100000_create_password_reset_tokens_table.php
   ...
✅ Migrations iniciais executadas com sucesso!

📍 [3/6] - Executando seeders...
✅ Seeders executados com sucesso!

📍 [4/6] - Rodando migrations restantes...
✅ Migrations restantes executadas com sucesso!

📍 [5/6] - Otimizando cache da aplicação...
✅ Cache otimizado com sucesso!

📍 [6/6] - Verificando status final...
✅ Processo de Fresh Personalizado concluído com sucesso!
🚀 Banco de dados pronto para uso!
```

## 🔍 **Solução de Problemas**

### **Permissão Negegada (Script Bash)**
```bash
chmod +x scripts/fresh-personalizado.sh
```

### **Migration Não Encontrada**
Verifique se o nome da migration está correto:
```bash
ls database/migrations/ | grep 2026_02_17
```

### **Erro de Conexão**
Verifique o arquivo `.env` e se o serviço de banco está rodando.

## 💡 **Dicas**

- Use `--force` em scripts automatizados (CI/CD)
- O comando Artisan permite customizar o ponto de parada
- Sempre verifique o `migrate:status` final para confirmar sucesso
