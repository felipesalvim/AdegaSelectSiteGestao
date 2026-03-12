<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado | Adega Select</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .success-card { background: white; border-radius: 16px; padding: 50px 40px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); text-align: center; max-width: 500px; width: 90%; animation: slideUp 0.5s ease-out; border-top: 5px solid #10b981; }
        .success-icon { width: 80px; height: 80px; background: #d1fae5; color: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 20px auto; animation: pulse 2s infinite; }
        h1 { color: var(--wine-dark); font-size: 2rem; margin-bottom: 10px; }
        p { color: #475569; font-size: 1.1rem; line-height: 1.6; margin-bottom: 30px; }
        .btn-vitrine { background: var(--wine-main); color: white; padding: 15px 30px; border: none; border-radius: 8px; font-size: 1.1rem; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-block; transition: 0.3s; width: 100%; box-sizing: border-box; }
        .btn-vitrine:hover { background: var(--wine-dark); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(99, 13, 13, 0.2); }
        .blockchain-notice { background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; font-size: 0.85rem; color: #64748b; margin-top: 25px; display: flex; align-items: center; justify-content: center; gap: 8px; }
        @keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); } 70% { box-shadow: 0 0 0 20px rgba(16, 185, 129, 0); } 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); } }
    </style>
</head>

<body>

    <div class="success-card">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        <h1>Pagamento Aprovado!</h1>
        <p>Saúde! 🍷 O seu PIX foi confirmado e o seu pedido já está sendo preparado com todo o cuidado pela nossa equipe.</p>
        
        <a href="index.php" class="btn-vitrine"><i class="fas fa-arrow-left"></i> Voltar para a Vitrine</a>

        <div class="blockchain-notice">
            <i class="fas fa-link" style="color: var(--gold);"></i> 
            <span>Seus vinhos possuem origem garantida via Blockchain.</span>
        </div>
    </div>

    <?php include 'vlibras.php'; ?>

</body>
</html>