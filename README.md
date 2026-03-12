# 🍷 Adega Select - Plataforma Omnichannel (E-commerce, ERP & Mobile)

Um ecossistema completo para a venda de vinhos premium. O projeto integra uma vitrine web responsiva, um checkout transparente (PIX), um painel de gestão corporativa (ERP) e atua como API central para um aplicativo mobile (Android). O grande diferencial é a simulação de segurança de procedência utilizando criptografia SHA-256 (Blockchain).

## 🚀 Arquitetura do Sistema

O sistema foi arquitetado em 3 camadas principais que convergem no mesmo banco de dados:

### 1. Front-end Web (Vitrine e Checkout)
* **Design Responsivo (Mobile First):** Layout fluido com Menu Sanduíche adaptável para qualquer dispositivo.
* **Catálogo Assíncrono:** Vinhos carregados via API (`fetch`) com filtros em tempo real por Categoria e Faixa de Preço (JavaScript Vanilla).
* **Sommelier IA (Chatbot Stateful):** Assistente virtual com memória de sessão (PHP `$_SESSION`) que atua como funil de vendas, convertendo visitantes em leads no WhatsApp.
* **Checkout Transparente PIX:** Integração direta com a API do Mercado Pago. Gera QR Code base64 e "Copia e Cola" sem tirar o cliente da página.

### 2. Back-end e Painel Administrativo (ERP)
* **Gestão de Inventário (Blockchain):** CRUD avançado. A inserção de vinhos é tratada como "Mineração de Bloco", gerando um Hash único. Alterações de preço e soft-delete (esgotamento) não quebram o histórico do produto.
* **Gestão de Pedidos Reais:** Assim que um PIX é gerado no Web ou no App, o sistema grava o pedido com os dados do cliente, itens da compra (carrinho) e status logístico.
* **Rastreabilidade Financeira:** O painel inclui links diretos para o portal do Mercado Pago, usando o `payment_id` para conciliação bancária instantânea.
* **KPIs Dinâmicos:** Dashboard exibe vendas do dia, visualizações do catálogo e total de ativos na vitrine.

### 3. Integração Mobile (App Android)
* O sistema web conta com uma seção dedicada (CTA) para download do `.apk`.
* O backend atua como API: o App envia as compras diretamente para o script `processar_pagamento.php` via POST, unificando as vendas web e mobile na mesma tabela `pedidos`.

---

## 🛠️ Tecnologias Utilizadas

* **Front-end:** HTML5, CSS3 (CSS Variables, Flexbox, CSS Grid, Glassmorphism), Vanilla JavaScript.
* **Back-end:** PHP 8+ (PDO para Banco de Dados, cURL para APIs, Sessions).
* **Banco de Dados:** MySQL (Relacional, com chaves estrangeiras e integridade de dados).
* **Gateways e APIs:** Mercado Pago API REST (v1/payments).
* **Acessibilidade:** Integração com a API do VLibras.

---

## 📂 Estrutura do Banco de Dados (`adega_db.sql`)

A arquitetura foi otimizada para evitar redundâncias e suportar o ecossistema omnichannel:

* `usuarios`: Gerenciamento de credenciais de administradores com senhas em hash (`password_hash`).
* `vinhos`: Armazena catálogo, miniaturas (URLs), metadados da safra e chaves SHA-256 de origem.
* `pedidos`: Tabela central que recebe as transações PIX geradas tanto pelo Site Web quanto pelo Aplicativo Mobile, atrelando o JSON do carrinho a um ID de pagamento.

---

## ⚙️ Como Rodar o Projeto Localmente

1. **Clone o repositório:**
   ```bash
   git clone [https://github.com/felipesalvim/AdegaSelectSiteGestao.git](https://github.com/felipesalvim/AdegaSelectSiteGestao.git)

```

2. **Configure o Servidor e o Banco:**
* Mova os arquivos para a pasta raiz do seu servidor local (ex: `htdocs` do XAMPP).
* Importe o arquivo SQL unificado no seu phpMyAdmin.
* Ajuste o arquivo `config.php` com as credenciais do seu banco de dados local.


3. **Configure as APIs de Pagamento:**
* Abra o arquivo `processar_pagamento.php`.
* Insira seu Access Token de Produção do Mercado Pago na variável `$access_token`.



---

## 🔒 Acesso ao Painel Administrativo

Para fins de teste e avaliação do projeto, utilize as credenciais padrão:

* **URL:** `localhost/sua-pasta/admin.php` (ou `login.php`)
* **E-mail:** `admin@adegaselect.com.br`
* **Senha:** (Configurada no momento da importação do Banco de Dados)

---

## 📱 Roadmap (Próximos Passos)

* [x] Unificação de Banco de Dados Web + Mobile.
* [x] Layout 100% Responsivo e Menu Sanduíche.
* [ ] Implementação de Webhooks do Mercado Pago para baixar o estoque automaticamente quando o PIX for pago.
* [ ] Geração de relatórios PDF/Excel das vendas mensais diretamente pelo Painel ERP.

---

*Projeto acadêmico focado em demonstração de arquitetura de software, integração de APIs financeiras e experiência do usuário.*

```

