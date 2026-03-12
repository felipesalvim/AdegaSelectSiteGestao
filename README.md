# 🍷 Adega Digital - E-commerce Premium & Sommelier IA

Um sistema completo de e-commerce de vinhos focado em alta conversão e experiência do usuário (UX). O projeto simula uma jornada de compra premium, contando com um Assistente Virtual Inteligente (Chatbot Stateful), validação de carrinho, e integração real de pagamentos via PIX (Checkout Transparente).

## 🚀 Funcionalidades do Sistema

O sistema foi dividido em 4 pilares principais:

### 1. Catálogo e Vitrine Dinâmica

* **Renderização Assíncrona:** Os vinhos são carregados via API (`fetch`) e injetados no DOM dinamicamente.
* **Filtros em Tempo Real:** Filtragem combinada por **Tipo** (Tinto, Branco, Rosé, Espumante) e **Preço** diretamente pelo Front-end (JavaScript), sem recarregar a página.
* **Certificação Blockchain (Conceito):** Exibição de Hash SHA-256 simulando o rastreio de autenticidade da garrafa.

### 2. Gestão de Carrinho (Local Storage)

* **Persistência de Dados:** Uso do `localStorage` com chave padronizada (`carrinho`) para manter os itens selecionados mesmo se o usuário fechar a aba.
* **Prevenção de Erros:** Bloqueio inteligente no ícone do carrinho que impede o usuário de acessar o Checkout se não houver produtos adicionados, evitando processamentos nulos.
* **Atualização em Tempo Real:** Badge numérico do carrinho com animação de *scale* sincronizada com a memória do navegador.

### 3. Sommelier IA (Máquina de Estados)

* **Chatbot Stateful (Com Memória):** Backend em PHP que utiliza `$_SESSION` para rastrear em qual etapa do funil o usuário está (Step 0: Nome, Step 1: Telefone, Step 2: Menu, Step 3: Recomendações).
* **Sincronização Front/Back:** O JavaScript envia o comando oculto `init` sempre que a janela do chat é aberta, garantindo que o PHP zere a memória e não crie loops de mensagens desencontradas.
* **Lead Generation:** Coleta Nome e WhatsApp, montando um link dinâmico para transbordo com um "Especialista Humano", enviando todo o histórico de navegação do cliente.
* **Renderização HTML:** Suporte a negrito (`<b>`) nativo no chat web e formatação Markdown (`*`) no redirecionamento para o app do WhatsApp.

### 4. Checkout Transparente (Mercado Pago)

* **UX Fluida:** O cliente não é redirecionado para fora do site. Todo o processo ocorre na página `checkout.php`.
* **Geração de PIX Nativo:** Integração com a API `v1/payments` do Mercado Pago.
* **Dados Dinâmicos:** Coleta Nome, E-mail e CPF do cliente e exibe o **QR Code (Base64)** e a chave **Copia e Cola** diretamente na interface gráfica, com botão interativo de cópia.

---

## 📂 Arquitetura e Estrutura de Arquivos

Abaixo está o mapa de arquivos do sistema e a responsabilidade de cada um:

```text
/app-vinhos
│
├── /css
│   └── style.css                 # Estilização global do sistema (Cores Wine, Layout Responsivo)
│
├── /img                          # Diretório de imagens estáticas e ícones do sistema
│   ├── capa-social.jpg           # Imagem de preview para compartilhamento em redes sociais
│   ├── hero-bg.jpg               # Imagem de fundo do banner principal
│   ├── icon-192.png              # Ícone para PWA (Android/iOS)
│   └── icon-512.png              # Ícone em alta resolução para PWA e splash screen
│
├── /js
│   ├── admin.js                  # Lógica do painel administrativo (Modais, CRUD e interação UI)
│   ├── app.js                    # Coração do Front-end (Carrinho, Filtros, Chatbot e Chamadas Fetch)
│   └── sw.js                     # Service Worker (Permite instalação do app e cache offline - PWA)
│
├── admin.php                     # Dashboard Restrito (Métricas, KPIs e Gestão do Catálogo)
├── api_vinhos.php                # Endpoint (API) que fornece o JSON com os dados dos vinhos
├── auth.php                      # Lógica de validação de login e proteção de rotas
├── blockchain.php                # Motor lógico para geração e validação das Hashes (Simulação Blockchain)
├── chatbot.php                   # Backend do Sommelier IA (Controle de Sessions e respostas do funil)
├── checkout.php                  # Tela de pagamento (Resumo do carrinho, Checkout Transparente PIX)
├── config.php                    # Configurações globais e conexão com o Banco de Dados (MySQL)
├── detalhes.php                  # Página de visualização estendida do produto (Rastreabilidade)
├── index.php                     # Página inicial (Vitrine dinâmica, Filtros e integração UI)
├── login.php                     # Tela de autenticação para acesso ao Painel Admin
├── logout.php                    # Script para encerramento seguro da sessão do Administrador
├── manifest.json                 # Arquivo de configuração do PWA (Cores, nome e comportamento de instalação)
├── processar_pagamento.php       # Integração com a API do Mercado Pago (Geração do PIX e QR Code)
├── README.md                     # Documentação oficial do projeto
└── vlibras.php                   # Componente de acessibilidade (Língua Brasileira de Sinais)

``

---

## 🛠️ Tecnologias Utilizadas

* **Front-end:** HTML5, CSS3, Vanilla JavaScript (ES6+).
* **Back-end:** PHP 8+ (Processamento de APIs, Sessions, cURL).
* **Armazenamento:** Browser LocalStorage (Front-end) e PHP Sessions (Back-end).
* **Gateway de Pagamento:** API REST do Mercado Pago (Checkout Transparente).
* **Túnel de Rede (Testes):** Ngrok (para expor o localhost via HTTPS e permitir a comunicação com a API do Banco Central/Mercado Pago).

---

## ⚙️ Como Rodar o Projeto Localmente

1. Clone este repositório para a pasta do seu servidor local (ex: `htdocs` no XAMPP ou `www` no WAMP).
2. Inicie o servidor Apache.
3. **Configuração do Pagamento:**
* Crie uma conta no [Mercado Pago Developers](https://www.mercadopago.com.br/developers/pt).
* Gere suas credenciais de Produção.
* Abra o arquivo `processar_pagamento.php` e insira seu **Access Token** na variável `$access_token`.


4. **Bypass de Segurança do Localhost:**
* Para que o Mercado Pago aceite a requisição do seu ambiente local, inicie o Ngrok apontando para a porta do seu servidor Apache:
```bash
ngrok http 80

```
### 5. Dashboard Administrativo (Gestão e KPIs)
* **Acesso Restrito:** Sistema de login com validação de sessão segura (Back-end).
* **Métricas em Tempo Real (KPIs):** Visão instantânea do total de vinhos ativos, esgotados e o total de visualizações da loja.
* **CRUD Temático (Blockchain):** Inserção de novos produtos tratada conceitualmente como "Mineração de Novo Vinho", mantendo a narrativa do rastreio de ponta a ponta.
* **Controle de Estoque e Preços:** Edição rápida e segura (permitindo alterar apenas o preço para não quebrar a hash do bloco do produto) e exclusão de itens com atualização instantânea na vitrine.

## 📱 Próximos Passos (Roadmap)

- [ ] Desenvolvimento da versão Mobile Nativa (Android Studio / Java).
- [ ] Implementação de Banco de Dados local (SQLite) no Mobile para funcionamento Offline.
- [ ] Sincronização em segundo plano dos pedidos offline com o banco de dados principal da nuvem.

---

*Desenvolvido para apresentação acadêmica e demonstração de arquitetura de software integrando Web e APIs Financeiras.*