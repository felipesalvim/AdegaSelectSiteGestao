<?php
include 'config.php';

// 1. Segurança Sênior: Valida se o ID existe e é um número (Evita SQL Injection)
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

// 2. Atualiza visualizações
$conn->query("UPDATE vinhos SET visualizacoes = visualizacoes + 1 WHERE id = $id");

// 3. Busca os dados de forma segura (Prepared Statement)
$stmt = $conn->prepare("SELECT * FROM vinhos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<h2 style='text-align:center; font-family:sans-serif; margin-top:50px;'>Garrafa não encontrada na rede.</h2>";
    exit;
}

$v = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adega Select | Rastreio de <?= htmlspecialchars($v['nome']) ?></title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .header-rastreio {
            background: var(--wine-dark);
            color: white;
            padding: 20px 40px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-voltar {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }

        .btn-voltar:hover {
            color: var(--gold);
            transform: translateX(-5px);
        }

        .certificado-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .certificado-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-top: 5px solid var(--gold);
            position: relative;
            overflow: hidden;
        }

        /* Marca d'água estilizada ao fundo */
        .certificado-card::after {
            content: '\f0c1';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            font-size: 15rem;
            color: rgba(197, 160, 40, 0.03);
            right: -20px;
            bottom: -40px;
            pointer-events: none;
        }

        .cert-header {
            text-align: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .cert-header h1 {
            color: var(--wine-main);
            margin: 0 0 10px 0;
            font-size: 2rem;
        }

        .cert-badge {
            background: #d4edda;
            color: #155724;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        /* Timeline do Blockchain */
        .timeline {
            position: relative;
            padding-left: 30px;
            margin-top: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 11px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-icon {
            position: absolute;
            left: -30px;
            top: 0;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: white;
            border: 2px solid var(--gold);
            color: var(--gold);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.7rem;
            box-shadow: 0 0 0 4px white;
        }

        .timeline-content {
            background: #f8fafc;
            padding: 15px 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .timeline-content h4 {
            margin: 0 0 8px 0;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .hash-box {
            background: #1e293b;
            color: #10b981;
            padding: 10px 15px;
            border-radius: 6px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.85rem;
            word-break: break-all;
            margin-top: 5px;
            border-left: 3px solid #10b981;
        }

        .hash-box.antigo {
            color: #94a3b8;
            border-left-color: #64748b;
        }
    </style>
</head>

<body>

    <header class="header-rastreio">
        <a href="index.php" class="btn-voltar"><i class="fas fa-arrow-left"></i> Voltar para a Loja</a>
        <div style="margin-left: auto; font-weight: bold; color: var(--gold);"><i class="fas fa-shield-alt"></i> Adega Select | Autenticidade</div>
    </header>

    <div class="certificado-container">
        <div class="certificado-card">

            <div class="cert-header">
                <i class="fas fa-wine-bottle" style="font-size: 3rem; color: var(--wine-main); margin-bottom: 15px;"></i>
                <h1><?= htmlspecialchars($v['nome']) ?></h1>
                <p style="color: #64748b; font-size: 1.1rem; margin-bottom: 15px;">
                    Safra: <strong><?= $v['safra'] ?></strong> | Tipo: <strong><?= $v['tipo'] ?></strong>
                </p>
                <div class="cert-badge"><i class="fas fa-check-circle"></i> Origem Verificada em Blockchain</div>
            </div>

            <h3 style="color: #333; margin-bottom: 20px;"><i class="fas fa-project-diagram"></i> Auditoria da Cadeia de Blocos</h3>

            <div class="timeline">

                <div class="timeline-item">
                    <div class="timeline-icon" style="border-color: #64748b; color: #64748b;"><i class="fas fa-link"></i></div>
                    <div class="timeline-content">
                        <h4>Bloco Gênesis / Anterior</h4>
                        <p style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">Este é o elo criptográfico que conecta este vinho ao histórico imutável da rede.</p>
                        <div class="hash-box antigo">
                            <?= $v['hash_anterior'] ?>
                        </div>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-icon" style="background: var(--wine-main); border-color: var(--wine-main); color: white;"><i class="fas fa-lock"></i></div>
                    <div class="timeline-content" style="border-color: #bbf7d0; background: #f0fdf4;">
                        <h4 style="color: #166534;">Assinatura do Produto Atual</h4>
                        <p style="font-size: 0.85rem; color: #166534; margin-bottom: 5px;">Hash SHA-256 único gerado no momento do engarrafamento e registo.</p>
                        <div class="hash-box">
                            <?= $v['hash_blockchain'] ?>
                        </div>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-icon" style="background: #10b981; border-color: #10b981; color: white;"><i class="fas fa-check"></i></div>
                    <div class="timeline-content" style="background: transparent; border: none; padding-left: 0;">
                        <h4 style="color: #10b981;">Integridade Validada</h4>
                        <p style="font-size: 0.85rem; color: #666;">Os dados não foram adulterados desde o registo no servidor.</p>
                    </div>
                </div>

            </div>

            <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px dashed #ccc;">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?= urlencode('https://adegaselect.com.br/detalhes.php?id=' . $v['id']) ?>" alt="QR Code de Rastreio" style="border-radius: 8px; padding: 5px; border: 1px solid #ddd;">
                <p style="font-size: 0.75rem; color: #999; margin-top: 10px;">Leia com o telemóvel para validar a autenticidade.</p>
            </div>

        </div>
    </div>

    <?php include 'vlibras.php'; ?>

</body>

</html>