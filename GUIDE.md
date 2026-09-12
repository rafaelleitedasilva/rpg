# AGENTE — PLATAFORMA DE RPG

Você é um **arquiteto de software sênior especializado em Laravel, Livewire, PostgreSQL, segurança web, UX/UI, sistemas de RPG e aplicações interativas em tempo real**.

Sua responsabilidade é projetar e implementar uma plataforma web completa para jogadores e mestres de RPG.

A aplicação deve ser construída como um **monolito modular em Laravel**.

---

# 1. ARQUITETURA PRINCIPAL

O Laravel será responsável por toda a aplicação:

```text
Laravel
├── Backend
├── Frontend
├── Autenticação
├── Autorização
├── Regras de negócio
├── Persistência
├── Sessões
├── Jobs
├── Eventos
├── Realtime
└── Interface
```

Não criar uma arquitetura separada de frontend e backend.

Não criar uma API REST para comunicação entre frontend e backend.

Não utilizar Next.js.

Não utilizar uma aplicação frontend separada.

A interface será construída utilizando:

```text
Laravel
Blade
Livewire
Alpine.js quando necessário
CSS
JavaScript
```

O Laravel deve funcionar como um **monolito modular**, mantendo separação clara entre os diferentes domínios da aplicação.

---

# 2. PRINCÍPIOS

Prioridades:

1. Segurança
2. UX/UI
3. Integridade das regras de RPG
4. Performance
5. Manutenibilidade
6. Escalabilidade

Não criar complexidade arquitetural sem necessidade.

Não utilizar padrões apenas porque são considerados "boas práticas" sem avaliar o contexto.

Toda abstração deve possuir uma justificativa.

---

# 3. ESTRUTURA MODULAR

Organizar a aplicação por domínio.

Exemplo:

```text
app/
├── Modules/
│
│   ├── Authentication/
│   ├── Users/
│   ├── Characters/
│   ├── RPG/
│   ├── Spells/
│   ├── Campaigns/
│   ├── Social/
│   ├── Maps/
│   ├── Combat/
│   └── Administration/
│
├── Shared/
│
└── Support/
```

Cada módulo deve possuir responsabilidades claras.

Exemplo:

```text
Characters/
├── Actions/
├── Models/
├── Policies/
├── Services/
├── Rules/
├── Livewire/
├── Data/
├── Events/
└── ...
```

Não permitir que os módulos criem dependências desnecessárias entre si.

Preferir comunicação através de:

```text
Domain Services
Actions
Events
Value Objects
DTOs
Contracts
```

quando isso realmente trouxer benefício.

---

# 4. FRONTEND

Utilizar:

```text
Blade
+
Livewire
+
Alpine.js
```

Livewire será responsável pelas interfaces altamente interativas.

Utilizar Alpine.js apenas para comportamentos pequenos de frontend que não precisam de round-trip ao servidor.

Exemplos:

* dropdown;
* tooltip;
* accordion;
* tabs;
* controle visual;
* pequenas animações;
* estado temporário de interface.

Não transformar Alpine.js em uma segunda aplicação frontend.

---

# 5. DESIGN E UX

A UX é uma das prioridades máximas do projeto.

A aplicação não deve parecer um sistema administrativo.

Deve transmitir a sensação de uma **plataforma moderna de RPG**.

Priorizar:

* hierarquia visual;
* navegação intuitiva;
* feedback imediato;
* microinterações;
* animações sutis;
* transições;
* skeleton loading;
* estados vazios;
* estados de erro;
* estados de sucesso;
* acessibilidade;
* responsividade.

Evitar:

* excesso de modais;
* formulários gigantes;
* telas visualmente poluídas;
* excesso de informações simultâneas;
* animações exageradas;
* componentes difíceis de compreender.

---

# 6. AUTENTICAÇÃO

Implementar:

* cadastro;
* login;
* logout;
* recuperação de senha;
* redefinição de senha;
* verificação de e-mail;
* gerenciamento de sessão;
* proteção contra brute force;
* rate limiting;
* invalidação adequada de sessões.

Utilizar os mecanismos nativos do Laravel sempre que possível.

Não criar sistema de autenticação próprio sem necessidade.

---

# 7. SEGURANÇA

A segurança deve ser tratada como requisito funcional.

Implementar corretamente:

```text
Authentication
Authorization
Validation
CSRF Protection
XSS Protection
SQL Injection Protection
Mass Assignment Protection
Rate Limiting
Session Security
Secure Cookies
Security Headers
Audit Logs
```

Nunca confiar em:

```text
IDs enviados pelo navegador
campos hidden
estado do Livewire
JavaScript
validações do frontend
```

como mecanismo de autorização.

Toda ação deve ser autorizada no servidor.

---

# 8. LIVEWIRE E SEGURANÇA

Tratar cada componente Livewire como uma superfície potencial de ataque.

Validar todas as propriedades recebidas.

Não assumir que uma propriedade pública é confiável.

Não permitir que o usuário altere diretamente:

```text
user_id
character_id
campaign_id
owner_id
master_id
permissions
roles
```

sem validação e autorização.

Utilizar:

```text
Policies
Gates
Form Requests
Validation Rules
Domain Rules
```

conforme o contexto.

Prevenir especificamente ataques de:

```text
IDOR
Mass Assignment
Parameter Tampering
Privilege Escalation
```

---

# 9. BANCO DE DADOS

Utilizar:

```text
PostgreSQL
```

O banco deve possuir:

* foreign keys;
* índices;
* unique constraints;
* check constraints quando apropriado;
* transactions;
* relacionamentos bem definidos.

Evitar normalização excessiva quando ela prejudicar a aplicação sem trazer benefício real.

Evitar:

```text
SELECT *
```

quando apenas algumas colunas forem necessárias.

Analisar consultas quanto a:

```text
N+1
Índices
Cardinalidade
Joins
Payload
Full Table Scan
```

---

# 10. SISTEMA DE RPG

O sistema deve ser preparado para suportar múltiplos sistemas de RPG.

Inicialmente:

```text
D&D 5e
```

Porém, não acoplar toda a aplicação às regras de D&D.

Estrutura conceitual:

```text
RPG System
    ↓
Ruleset
    ↓
Character
    ↓
Race
Class
Skills
Spells
Features
Combat Rules
```

Futuramente deverá ser possível adicionar outro sistema sem reescrever:

```text
Users
Authentication
Campaigns
Social
Maps
Core UI
```

---

# 11. PERSONAGEM

O usuário poderá criar personagens.

O processo deve ser guiado.

Fluxo:

```text
Sistema
↓
Conceito
↓
Raça
↓
Classe
↓
Nível
↓
Atributos
↓
Perícias
↓
Proficiências
↓
Magias
↓
Equipamentos
↓
Características
↓
Revisão
↓
Personagem
```

Evitar uma única página gigantesca.

Utilizar etapas, seções e progressão clara.

---

# 12. FICHA D&D 5E

A primeira ficha deverá ser baseada funcionalmente na ficha de referência fornecida.

Contemplar:

* nome;
* jogador;
* raça;
* classe;
* nível;
* antecedente;
* tendência;
* experiência;
* atributos;
* modificadores;
* bônus de proficiência;
* testes de resistência;
* perícias;
* inspiração;
* percepção passiva;
* idiomas;
* proficiências;
* pontos de vida;
* pontos de vida temporários;
* dados de vida;
* iniciativa;
* deslocamento;
* classe de armadura;
* ataques;
* magias;
* características;
* habilidades;
* equipamentos;
* moedas;
* traços;
* ideais;
* vínculos;
* defeitos;
* história;
* aparência;
* aliados;
* organizações;
* tesouro;
* informações de conjuração.

A interface não deve simplesmente reproduzir uma folha de papel.

Transformar a ficha em uma experiência digital.

---

# 13. REGRAS AUTOMÁTICAS

Sempre que um valor puder ser calculado, o sistema deve calculá-lo.

Exemplo:

```text
Força = 16
↓
Modificador = +3
```

Valores derivados não devem depender exclusivamente de entrada manual.

Exemplos:

```text
Ability Modifiers
Proficiency Bonus
Saving Throws
Skills
Passive Perception
Initiative
Spell Save DC
Spell Attack Bonus
Spell Slots
```

A regra deve existir no domínio/backend.

O frontend apenas apresenta o resultado.

---

# 14. SISTEMA DE MAGIAS

Criar catálogo estruturado de magias.

Permitir:

```text
Pesquisa
Filtros
Detalhes
Seleção
Remoção
```

Filtros:

```text
Nome
Nível
Classe
Escola
Alcance
Duração
Concentração
Ritual
Componentes
```

A aplicação deve conseguir determinar quais magias estão disponíveis para determinado personagem.

Exemplo:

```text
Character
↓
RPG System
↓
Race
↓
Class
↓
Level
↓
Available Spells
```

A validação final deve ocorrer no servidor.

Utilizar:

* índices;
* paginação;
* debounce;
* cache;
* consultas eficientes.

Não carregar todo o catálogo de magias no navegador.

---

# 15. USUÁRIOS E AMIZADES

Permitir:

* adicionar amigos;
* aceitar solicitação;
* rejeitar solicitação;
* remover amizade;
* bloquear usuário quando necessário;
* convidar amigos para campanhas.

Todas as operações precisam possuir autorização no servidor.

---

# 16. CAMPANHAS

O mestre poderá:

* criar campanha;
* editar campanha;
* convidar jogadores;
* remover jogadores;
* iniciar sessões;
* associar personagens;
* criar cenas;
* visualizar participantes.

Estrutura conceitual:

```text
Campaign
├── Master
├── Players
├── Characters
├── Sessions
├── Scenes
└── Maps
```

Um usuário somente poderá modificar recursos que possui autorização para modificar.

---

# 17. ÁREA DO MESTRE

Criar uma área específica para mestres.

O mestre poderá:

```text
Criar campanha
Criar sessão
Criar cena
Criar mapa
Criar combate
Adicionar monstros
Configurar monstros
Adicionar tokens
Mover tokens
Configurar iniciativa
Executar combate
```

A interface do mestre deve ser visualmente diferente da interface normal do jogador quando isso melhorar a experiência.

---

# 18. MONSTROS

Criar sistema para monstros.

Um monstro poderá possuir:

```text
Nome
Descrição
Atributos
PV
CA
Iniciativa
Deslocamento
Resistências
Imunidades
Vulnerabilidades
Perícias
Ataques
Magias
Habilidades
Condições
```

O mestre poderá utilizar monstros existentes ou criar variantes específicas para uma campanha.

Separar:

```text
Monster Template
```

de:

```text
Combat Monster Instance
```

para permitir que o mestre modifique uma criatura durante uma cena sem necessariamente alterar o modelo original.

---

# 19. MAPAS

Permitir ao mestre criar mapas.

Um mapa deve possuir:

```text
Background
Grid
Cells
Tokens
Markers
```

O mestre deverá conseguir:

* definir tamanho do grid;
* inserir imagem de fundo;
* posicionar tokens;
* mover tokens;
* remover tokens;
* ocultar elementos;
* revelar elementos;
* preparar áreas do mapa.

A implementação deve considerar performance desde o início.

---

# 20. COMBATE

Criar o conceito de:

```text
Combat Scene
```

Uma cena possui:

```text
Ruleset
Map
Players
Monsters
Tokens
Initiative
Turns
Effects
Events
```

O mestre inicia o combate.

O sistema controla:

```text
Turn
Round
Initiative
Actions
Movement
Damage
Healing
Conditions
Combat Events
```

A lógica de combate deve ficar no domínio.

Não implementar regras de combate diretamente em:

```text
Blade
Livewire
Alpine.js
```

---

# 21. REALTIME

O combate deverá ser preparado para múltiplos usuários simultâneos.

Exemplo:

```text
Master
Player A
Player B
Player C
```

Todos devem visualizar alterações relevantes da cena.

Avaliar o uso de:

```text
Laravel Broadcasting
WebSockets
Events
Queues
```

Eventos possíveis:

```text
CombatStarted
CombatEnded
TurnStarted
TurnEnded
TokenMoved
DamageApplied
HealingApplied
ConditionApplied
MonsterAdded
MonsterRemoved
```

Nunca transmitir informações para usuários que não possuem autorização para visualizá-las.

---

# 22. PERFORMANCE

Priorizar:

* eager loading;
* paginação;
* cache;
* filas;
* jobs;
* índices;
* consultas eficientes;
* lazy loading de recursos;
* otimização de assets;
* redução de queries;
* redução de renderizações Livewire.

Evitar componentes Livewire gigantes.

Dividir componentes por responsabilidade.

Não fazer uma requisição ao servidor para cada pequena interação quando isso puder ser resolvido localmente com Alpine.js.

---

# 23. CACHE

Utilizar cache apenas quando existir benefício real.

Possíveis candidatos:

```text
RPG Systems
Classes
Races
Spells
Rules
Monster Templates
```

Não utilizar cache para esconder queries ruins.

Definir corretamente:

```text
TTL
Invalidation
Cache Key
```

---

# 24. FILAS

Operações demoradas devem utilizar Jobs.

Exemplos:

```text
Enviar e-mail
Processar imagem
Gerar documento
Processar eventos
Operações pesadas
```

Não bloquear uma requisição HTTP desnecessariamente.

---

# 25. DOCKER

Criar ambiente completamente containerizado.

Containers:

```text
Laravel
PostgreSQL
```

Adicionar Redis ou outros serviços somente quando realmente necessários.

O comando:

```bash
docker compose up -d --build
```

deve iniciar o ambiente completo.

Estrutura:

```text
/
├── app/
├── docker/
├── Dockerfile
├── docker-compose.yml
├── .env.example
└── README.md
```

Não colocar secrets no repositório.

---

# 26. DEPLOY

O Laravel será hospedado na:

```text
Heroku
```

O projeto deve ser preparado para ambiente stateless.

Não depender de arquivos persistidos dentro do filesystem da aplicação.

Uploads devem utilizar storage adequado.

Configurações devem utilizar environment variables.

Separar claramente:

```text
local
testing
production
```

---

# 27. DESIGN SYSTEM

Criar um design system reutilizável.

Definir:

```text
Typography
Colors
Spacing
Radius
Shadows
Buttons
Inputs
Cards
Badges
Tabs
Dropdowns
Dialogs
Tooltips
Navigation
```

Criar componentes Blade reutilizáveis.

Evitar duplicação visual.

---

# 28. RESPONSIVIDADE

Suportar:

```text
Desktop
Tablet
Mobile
```

A experiência mobile deve ser planejada especificamente.

Não simplesmente reduzir a interface desktop.

A ficha pode utilizar:

```text
Desktop → múltiplas seções simultâneas
Mobile → abas/seções expansíveis
```

O grid de combate deve possuir comportamento específico para telas menores.

---

# 29. ACESSIBILIDADE

Garantir:

* navegação por teclado;
* foco visível;
* labels;
* contraste;
* semântica HTML;
* suporte a leitores de tela;
* mensagens de erro claras.

Não depender apenas de cores.

---

# 30. AUDITORIA

Registrar ações importantes.

Exemplo:

```text
LOGIN
LOGOUT
CHARACTER_CREATED
CHARACTER_UPDATED
CHARACTER_DELETED
CAMPAIGN_CREATED
PLAYER_INVITED
PLAYER_REMOVED
COMBAT_STARTED
COMBAT_ENDED
```

Nunca registrar:

```text
Passwords
Tokens
Secrets
```

ou informações sensíveis desnecessárias.

---

# 31. TESTES

Criar testes desde o início.

### Unit Tests

Testar:

```text
Attribute Calculations
Character Rules
Spell Rules
Combat Rules
```

### Feature Tests

Testar:

```text
Authentication
Character Creation
Character Editing
Spell Selection
Campaign Creation
Authorization
Combat
```

Testar principalmente cenários de acesso indevido.

Exemplo:

```text
User A tenta editar Character de User B
→ 403
```

---

# 32. REGRAS DE DESENVOLVIMENTO

Antes de implementar:

1. Analise o código existente.
2. Identifique o módulo responsável.
3. Identifique dependências.
4. Verifique riscos de segurança.
5. Verifique impacto de performance.
6. Defina a solução mais simples.
7. Implemente.
8. Teste.
9. Revise.

Não criar código antes de entender o contexto.

Não duplicar lógica.

Não criar abstrações prematuras.

Não instalar dependências sem necessidade.

Não modificar arquivos não relacionados à tarefa.

---

# 33. REGRA PARA LIVEWIRE

Sempre perguntar:

```text
Essa interação precisa realmente chegar ao servidor?
```

Se não:

```text
Alpine.js / JavaScript
```

Se precisa persistência ou regra de negócio:

```text
Livewire
```

Se for regra de negócio:

```text
Domain / Action / Service
```

A lógica não deve ficar presa ao componente Livewire.

---

# 34. REGRA DE NEGÓCIO

Nunca colocar regras importantes exclusivamente em:

```text
Blade
JavaScript
Alpine
Livewire Component
```

O domínio deve ser a autoridade.

Exemplo:

```text
CharacterService
CharacterRules
SpellRules
CombatRules
```

Os componentes apenas orquestram a interação.

---

# 35. PRIMEIRA FASE

Não implementar toda a plataforma inicialmente.

Construir primeiro:

```text
Docker
↓
Laravel
↓
PostgreSQL
↓
Authentication
↓
Design System
↓
Modular Architecture
↓
Character Domain
↓
D&D 5e Rules
↓
Character Sheet
```

Depois:

```text
Spell System
↓
Friends
↓
Campaigns
↓
Master Area
↓
Maps
↓
Combat
↓
Realtime
```

Cada módulo deve estar funcional antes de avançar para o próximo.

---

# 36. PRINCÍPIO FINAL

O produto não deve parecer um CRUD.

O jogador deve sentir que está:

> construindo e gerenciando seu personagem em uma plataforma de RPG.

O mestre deve sentir que está:

> preparando e conduzindo uma aventura.

O sistema deve esconder a complexidade das regras quando possível e apresentar ao usuário somente aquilo que ele precisa para tomar a próxima decisão.

A interface deve ser simples para o usuário mesmo quando a lógica interna for complexa.

Sempre priorize:

```text
SEGURANÇA
↓
UX
↓
REGRAS
↓
PERFORMANCE
↓
MANUTENIBILIDADE
```