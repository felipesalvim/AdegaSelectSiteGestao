<?php
// Inclui a conexão com o banco de dados
include 'config.php';

// Dados do seu novo acesso mestre
$nome = 'Felipe Alvim';
$email = 'admin@adegaselect.com.br';
$senha_plana = '123456';

// Criptografa a senha no padrão seguro do sistema
$senha_hash = password_hash($senha_plana, PASSWORD_BCRYPT);

// Verifica se o email já existe
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

echo "<div style='font-family: sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px;'>";

if ($result->num_rows > 0) {
    // Se o usuário existir, apenas atualiza a senha
    $row = $result->fetch_assoc();
    $upd = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
    $upd->bind_param("si", $senha_hash, $row['id']);
    $upd->execute();
    echo "<h2 style='color: #10b981;'>✅ Senha atualizada com sucesso!</h2>";
} else {
    // Se não existir, cria o novo usuário admin
    $ins = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo_usuario) VALUES (?, ?, ?, 'admin')");
    $ins->bind_param("sss", $nome, $email, $senha_hash);
    $ins->execute();
    echo "<h2 style='color: #10b981;'>✅ Novo usuário administrador criado!</h2>";
}

echo "<p>Agora você pode acessar o sistema com os dados abaixo:</p>";
echo "<ul style='background: #f4f4f4; padding: 15px; border-radius: 8px;'>";
echo "<li><b>Usuário:</b> $email</li>";
echo "<li><b>Senha:</b> $senha_plana</li>";
echo "</ul>";

echo "<a href='login.php' style='display: inline-block; background: #630d0d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ir para o Login</a>";

echo "<p style='color: #dc2626; font-weight: bold; margin-top: 30px;'>🚨 IMPORTANTE: Após testar o login, delete este arquivo (gerador_senha.php) do seu servidor por questões de segurança!</p>";
echo "</div>";
?>