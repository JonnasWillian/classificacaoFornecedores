# Sistema de Gerenciamento de Demandas e Fornecedores

Este sistema permite gerenciar demandas e fornecedores, atribuindo fornecedores às demandas com base na proximidade geográfica e em um mecanismo de balanceamento (round-robin).

## Requisitos

1. **Php 8.2 ou superior**

## Primeiro passos

1. **Copiar o .env.example**: copie o arquivo `.env.example` e altere somente as informações do banco de dados para iniciar
- `DB_CONNECTION`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

2. **Instale as depêndencias do composer**: execute o comando `composer install` e aguarde a instalação

3. **Instale as depêndencias do vue**: execute o comando `npm install` e aguarde a instalação

4. **Inicie a aplicação**: execute o comando `php artisan serve` e `npm run dev`

## Estrutura do Banco de Dados

O sistema usa três tabelas principais:

1. **fornecedors**: Armazena os dados dos fornecedores
   - `id` (chave primária)
   - `razao_social`
   - `cep`
   - `created_at`, `updated_at`

2. **demandas**: Armazena as demandas do sistema
   - `id` (chave primária)
   - `descricao`
   - `created_at`, `updated_at`

## Funcionalidades Principais

### 1. Solicitar Demanda

Endpoint: `POST /api/demandas/{demanda}/solicitar`

Esta funcionalidade associa a demanda a um fornecedor baseado em:
- Proximidade geográfica (usando CEP)
- Mecanismo de round-robin para balanceamento

```json
// Exemplo de requisição
{
  "cep_execucao": "01001000"
}

// Exemplo de resposta
{
  "demanda": {...},
  "cep_execucao": "01001000",
  "fornecedores_ordenados": [...],
  "fornecedores_associados": [...]
}
```

### 2. Visualizar Demandas de um Fornecedor

Endpoint: `GET /api/fornecedores/{fornecedor}/demandas`

Esta funcionalidade mostra as estatísticas das demandas associadas a um fornecedor:
- Total de demandas
- Distribuição por mês
- Posição média no round-robin
- Lista das 5 demandas mais recentes

```json
// Exemplo de resposta
{
  "fornecedor": {...},
  "estatisticas": {
    "total_demandas": 15,
    "demandas_por_mes": [...],
    "posicao_media": 3.5
  },
  "demandas_recentes": [...]
}
```

## Populando o Banco de Dados

Para popular o banco de dados com dados de teste, execute:

```bash
php artisan db:seed
```

Isso irá criar:
- 5 fornecedores com CEPs diferentes
- 20 demandas com status variados