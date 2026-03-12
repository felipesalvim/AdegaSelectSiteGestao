<?php
session_start();
// Se o usuário já estiver logado, redireciona direto para o painel, evitando a tela de login
if(isset($_SESSION['logado'])) {
    header("Location: admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Adega Select Admin</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* CSS Exclusivo para a Tela de Login */
        body {
            /* Fundo degradê elegante com as cores do vinho */
            background: linear-gradient(135deg, var(--wine-dark), var(--wine-main));
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
            animation: fadeInUp 0.5s ease-out;
        }
        .login-card h2 {
            color: var(--wine-dark);
            margin-bottom: 25px;
            font-size: 1.8rem;
        }
        .login-icon {
            font-size: 3.5rem;
            color: var(--gold);
            margin-bottom: 15px;
        }
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }
        .input-group input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: 0.3s;
            box-sizing: border-box;
        }
        .input-group input:focus {
            border-color: var(--wine-main);
            outline: none;
            box-shadow: 0 0 8px rgba(99, 13, 13, 0.2);
        }
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, var(--wine-main), var(--wine-dark));
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .error-msg {
            color: #e74c3c;
            background: #fadbd8;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            /* Só exibe a div de erro se o PHP identificar o parâmetro ?erro=1 na URL */
            display: <?php echo isset($_GET['erro']) ? 'block' : 'none'; ?>;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="login-card">
        <i class="fas fa-wine-glass-alt login-icon"></i>
        <h2>Acesso Restrito</h2>
        
        <div class="error-msg">
            <i class="fas fa-exclamation-triangle"></i> Usuário ou senha incorretos.
        </div>

        <form action="auth.php" method="POST">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="usuario" placeholder="Nome de Usuário" required autofocus>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="senha" placeholder="Senha de Acesso" required>
            </div>
            <button type="submit" class="btn-login">Entrar no Sistema <i class="fas fa-sign-in-alt" style="margin-left: 5px;"></i></button>
        </form>
        
        <div style="margin-top: 25px; font-size: 0.9rem;">
            <a href="index.php" style="color: #666; text-decoration: none; transition: 0.3s;" onmouseover="this.style.color='var(--wine-main)'" onmouseout="this.style.color='#666'">
                <i class="fas fa-arrow-left"></i> Voltar para a Vitrine
            </a>
        </div>
    </div>

    <?php include 'vlibras.php'; ?>

</body>
</html>