<?php
// 1. Garante que a sessão seja iniciada com segurança
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config.php';

// 2. Proteção contra acesso direto via URL (Verbo HTTP)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

// 3. Validação e sanitização de dados vazios
// O input do HTML ainda se chama 'usuario', mas vamos tratá-lo como email no banco
$login = $_POST['usuario'] ?? '';
$senha = $_POST['senha'] ?? '';

if (empty(trim($login)) || empty($senha)) {
    header("Location: login.php?erro=1");
    exit;
}

try {
    // 4. Consulta segura (Prepared Statement)
    // CORRIGIDO: Buscando pelas colunas 'email' e 'tipo_usuario' que existem no banco
    $stmt = $conn->prepare("SELECT id, senha, nome, tipo_usuario FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($u = $result->fetch_assoc()) {

        // 5. Validação do Hash BCRYPT
        if (password_verify($senha, $u['senha'])) {

            // Regenera o ID da sessão para evitar Session Fixation
            session_regenerate_id(true);

            // 6. Popula a sessão com os dados do usuário logado
            $_SESSION['logado'] = true;
            $_SESSION['user_id'] = $u['id']; 
            $_SESSION['user_nome'] = $u['nome'];
            // CORRIGIDO: Mapeando para o nome correto da coluna
            $_SESSION['user_nivel'] = $u['tipo_usuario'];

            header("Location: admin.php");
            exit;
        }
    }

    // 7. DEFESA CONTRA FORÇA BRUTA
    sleep(1);
    header("Location: login.php?erro=1");
    exit;
} catch (Exception $e) {
    error_log("Erro no Login: " . $e->getMessage());
    header("Location: login.php?erro=1");
    exit;
}