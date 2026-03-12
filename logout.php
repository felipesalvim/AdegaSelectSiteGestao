<?php
// 1. Inicia a sessão para podermos manipulá-la
session_start();

// 2. Esvazia todas as variáveis da sessão (Limpa a memória do PHP)
$_SESSION = array();

// 3. Invalida o Cookie de sessão no navegador do usuário (Segurança Máxima)
// Isso destrói a chave que ligava o navegador ao servidor
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Finalmente, destrói a sessão no servidor
session_destroy();

// 5. Redireciona para o login
// (Opcional: você pode adicionar um ?logout=1 se quiser mostrar uma mensagem verde "Você saiu com sucesso" na login.php depois)
header("Location: login.php");
exit;
?>