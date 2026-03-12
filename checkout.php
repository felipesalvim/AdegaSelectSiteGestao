<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Adega Select</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Cabeçalho de Checkout Minimalista */
        .checkout-header {
            background: white;
            padding: 25px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-bottom: 3px solid var(--wine-main);
        }

        .checkout-header h1 {
            color: var(--wine-dark);
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .checkout-header p {
            color: #10b981;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
        }

        /* Estrutura Principal */
        .checkout-container {
            max-width: 1050px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 30px;
        }

        .checkout-card {
            background: white;
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border: 1px solid #f1f5f9;
        }

        .checkout-card h3 {
            color: var(--wine-dark);
            margin-top: 0;
            font-size: 1.3rem;
            padding-bottom: 15px;
            margin-bottom: 25px;
            border-bottom: 2px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Tabela do Carrinho */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            color: #64748b;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 12px;
        }

        td {
            padding: 18px 0;
            border-bottom: 1px solid #f8fafc;
            vertical-align: middle;
            color: #334155;
        }

        .item-nome {
            font-weight: 600;
            font-size: 1.05rem;
        }

        .item-preco {
            color: var(--wine-dark);
            font-weight: bold;
            font-size: 1.1rem;
        }

        .btn-remove {
            background: #fee2e2;
            color: #ef4444;
            border: none;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .btn-remove:hover {
            background: #fca5a5;
            transform: scale(1.05);
        }

        /* Caixa de Total */
        .total-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 25px;
            border-radius: 12px;
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-box span:first-child {
            color: #475569;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .total-valor {
            font-size: 2rem;
            font-weight: 800;
            color: var(--wine-main);
        }

        /* Formulário de Pagamento Premium */
        #form-pix {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
        }

        .pix-alert {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 1rem;
            color: #334155;
            transition: all 0.3s ease;
            background: white;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--wine-main);
            box-shadow: 0 0 0 3px rgba(99, 13, 13, 0.1);
        }

        .form-group input::placeholder {
            color: #94a3b8;
        }

        /* Botão PIX */
        .btn-pix {
            background: linear-gradient(135deg, #00bdae, #009688);
            color: white;
            width: 100%;
            padding: 18px;
            border: none;
            border-radius: 10px;
            font-size: 1.15rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 189, 174, 0.3);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-pix:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 189, 174, 0.4);
        }

        .btn-pix:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        /* Área do QR Code gerado */
        #pix-qrcode-area {
            display: none;
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 12px;
            border: 2px solid #00bdae;
            box-shadow: 0 10px 25px rgba(0, 189, 174, 0.15);
        }

        #img-qrcode {
            width: 220px;
            height: 220px;
            margin: 15px auto;
            display: block;
            border-radius: 8px;
            padding: 10px;
            border: 1px solid #e2e8f0;
        }

        #texto-copia-cola {
            width: 100%;
            height: 70px;
            font-family: monospace;
            font-size: 0.85rem;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background: #f8fafc;
            color: #475569;
            resize: none;
            margin-bottom: 15px;
        }

        .btn-copy {
            background: #334155;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-copy:hover {
            background: #1e293b;
        }

        /* Selo de Segurança */
        .trust-badge {
            text-align: center;
            margin-top: 15px;
            font-size: 0.8rem;
            color: #64748b;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }

        @media (max-width: 850px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }

            .checkout-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="checkout-header">
            <img src="img/adega-select.png" alt="Adega Select" style="height: 100px; width: auto;">
        <h1>Finalizar Pedido</h1>
        <p><i class="fas fa-shield-alt"></i> Ambiente Seguro - Integração Oficial Mercado Pago</p>
    </div>

    <div class="checkout-container">

        <div class="checkout-card">
            <h3><i class="fas fa-shopping-basket" style="color: var(--gold);"></i> Seus Vinhos</h3>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Qtd</th>
                        <th>Preço</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tabela-checkout-body">
                </tbody>
            </table>

            <div class="total-box">
                <span>Total a Pagar:</span>
                <span class="total-valor" id="checkout-total">R$ 0,00</span>
            </div>

            <div style="text-align: center; margin-top: 25px;">
                <a href="index.php" style="color: #64748b; text-decoration: none; font-weight: 600; transition: 0.2s;" onmouseover="this.style.color='var(--wine-main)'" onmouseout="this.style.color='#64748b'"><i class="fas fa-arrow-left"></i> Continuar Comprando</a>
            </div>
        </div>

        <div class="checkout-card">
            <h3><i class="fab fa-pix" style="color: #00bdae;"></i> Pagamento via PIX</h3>

            <form id="form-checkout">

                <div id="pix-inputs">
                    <div id="form-pix">
                        <div class="pix-alert">
                            <i class="fas fa-bolt"></i> Pague rápido e com segurança. O pedido é aprovado na hora.
                        </div>

                        <div class="form-group">
                            <label>Nome Completo</label>
                            <input type="text" id="pix-nome" placeholder="Digite seu nome" required autocomplete="name">
                        </div>

                        <div style="display: flex; gap: 15px;">
                            <div class="form-group" style="flex: 1;">
                                <label>E-mail</label>
                                <input type="email" id="pix-email" placeholder="seu@email.com" required autocomplete="email">
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label>CPF</label>
                                <input type="text" id="pix-cpf" placeholder="000.000.000-00" required>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="btn-submit-payment" class="btn-pix" onclick="processarPagamento()">
                        <i class="fab fa-pix"></i> Gerar Código PIX
                    </button>

                    <div class="trust-badge">
                        <i class="fas fa-lock"></i> Pagamento processado com segurança pelo Mercado Pago
                    </div>
                </div>

                <div id="pix-qrcode-area">
                    <h4 style="color: var(--wine-dark); margin-top: 0; font-size: 1.2rem;">Escaneie o QR Code</h4>
                    <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 10px;">Abra o app do seu banco e aponte a câmera.</p>

                    <img id="img-qrcode" src="" alt="QR Code PIX">

                    <p style="font-size: 0.9rem; color: #475569; margin: 20px 0 10px 0; font-weight: bold;">Ou utilize a opção Copia e Cola:</p>
                    <textarea id="texto-copia-cola" readonly></textarea>

                    <button type="button" onclick="copiarPix()" class="btn-copy">
                        <i class="fas fa-copy"></i> Copiar Código PIX
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script src="js/app.js?v=<?= time(); ?>"></script>

    <script>
        // Formatação automática do CPF enquanto o usuário digita
        document.getElementById('pix-cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            if (value.length > 9) value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, '$1.$2.$3-$4');
            else if (value.length > 6) value = value.replace(/^(\d{3})(\d{3})(\d{3}).*/, '$1.$2.$3');
            else if (value.length > 3) value = value.replace(/^(\d{3})(\d{3}).*/, '$1.$2');
            e.target.value = value;
        });

        async function processarPagamento() {
            let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

            if (carrinho.length === 0) {
                alert("Seu carrinho está vazio!");
                return;
            }

            const nome = document.getElementById('pix-nome').value.trim();
            const email = document.getElementById('pix-email').value.trim();
            const cpf = document.getElementById('pix-cpf').value.trim();

            if (!nome || !email || !cpf) {
                alert("⚠️ Por favor, preencha todos os campos (Nome, E-mail e CPF) para gerar o PIX.");
                return;
            }

            if (cpf.replace(/\D/g, '').length !== 11) {
                alert("⚠️ Por favor, insira um CPF válido com 11 dígitos.");
                return;
            }

            const btnSubmit = document.getElementById('btn-submit-payment');
            btnSubmit.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Conectando ao Banco...';
            btnSubmit.disabled = true;

            try {
                const response = await fetch('processar_pagamento.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        carrinho: carrinho,
                        nome: nome,
                        email: email,
                        cpf: cpf
                    })
                });

                const data = await response.json();

                if (data.status === 'success') {
                    // Esconde os inputs e mostra o QR Code com fade in
                    document.getElementById('pix-inputs').style.display = 'none';

                    const qrArea = document.getElementById('pix-qrcode-area');
                    qrArea.style.display = 'block';
                    qrArea.style.animation = 'fadeIn 0.5s';

                    document.getElementById('img-qrcode').src = 'data:image/jpeg;base64,' + data.qr_code_base64;
                    document.getElementById('texto-copia-cola').value = data.qr_code_texto;

                    // Esvaziar o carrinho
                    localStorage.removeItem('carrinho');

                    // 🚀 NOVO: Fica escutando o pagamento do PIX automaticamente
                    let paymentId = data.payment_id;
                    let verificacaoPix = setInterval(async () => {
                        try {
                            let statusReq = await fetch(`consultar_status.php?id=${paymentId}`);
                            let statusData = await statusReq.json();

                            // Se o Mercado Pago disser que foi aprovado, redireciona!
                            if (statusData.status_pagamento === 'approved') {
                                clearInterval(verificacaoPix); // Para o espião
                                window.location.href = 'sucesso.php'; // Vai pra tela de obrigado!
                            }
                        } catch (e) {
                            console.log("Aguardando pagamento...");
                        }
                    }, 4000); // Checa a cada 4 segundos

                } else {
                    alert("Erro: " + data.message);
                    btnSubmit.innerHTML = '<i class="fab fa-pix"></i> Gerar Código PIX';
                    btnSubmit.disabled = false;
                }
            } catch (error) {
                alert("Erro de comunicação com o servidor Mercado Pago.");
                btnSubmit.innerHTML = '<i class="fab fa-pix"></i> Gerar Código PIX';
                btnSubmit.disabled = false;
            }
        }

        function copiarPix() {
            const copyText = document.getElementById("texto-copia-cola");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);

            const btnCopy = document.querySelector('.btn-copy');
            const originalText = btnCopy.innerHTML;
            btnCopy.innerHTML = '<i class="fas fa-check"></i> Copiado!';
            btnCopy.style.background = '#10b981'; // Muda pra verde confirmando

            setTimeout(() => {
                btnCopy.innerHTML = originalText;
                btnCopy.style.background = '#334155';
            }, 3000);
        }
    </script>

    <?php include 'vlibras.php'; ?>

</body>

</html>