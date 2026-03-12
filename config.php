<?php
// ==========================================
// 1. SEGURANÇA DE SESSÃO AVANÇADA
// ==========================================
ini_set('session.cookie_httponly', 1); // Impede roubo de sessão via JavaScript (XSS)
ini_set('session.use_only_cookies', 1); // Impede passagem de ID de sessão via URL
ini_set('session.cookie_samesite', 'Lax'); // Nova proteção do HTML5 contra ataques CSRF

// Evita o erro "A session had already been started" do PHP
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================================
// 2. CONFIGURAÇÕES REGIONAIS
// ==========================================
// Define o fuso horário correto para os registros do banco e da vitrine
date_default_timezone_set('America/Fortaleza');

// ==========================================
// 3. CREDENCIAIS DO BANCO DE DADOS (Constantes)
// ==========================================
// Usar define() é o padrão de mercado, facilita na hora de subir para uma hospedagem real
define('DB_HOST', 'localhost'); // Na Hostgator geralmente continua localhost
define('DB_USER', 'root'); // Nome de usuário do BD no cPanel
define('DB_PASS', ''); // Senha que você criou para o usuário do BD
define('DB_NAME', ''); // Nome do banco que vimos no .sql

// ==========================================
// 4. CONEXÃO SEGURA E TRATAMENTO DE EXCEÇÕES
// ==========================================
// Força o MySQLi a lançar Exceções (Erros fatais rastreáveis) em vez de simples Warnings
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    // 1. Salva o erro real (com senhas e caminhos) apenas no log oculto do servidor
    error_log("Falha Crítica no Banco de Dados: " . $e->getMessage());

    // 2. Trava o site e exibe uma mensagem genérica para o usuário/hacker
    http_response_code(500);
    exit("<div style='font-family: sans-serif; text-align: center; margin-top: 100px;'>
            <h2 style='color: #dc2626;'>Erro 500: Serviço Indisponível</h2>
            <p>Não foi possível estabelecer comunicação com o servidor de dados.</p>
          </div>");
}
