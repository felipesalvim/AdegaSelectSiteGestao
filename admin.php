<?php
include 'config.php';
if (!isset($_SESSION['logado'])) {
    header("Location: login.php");
    exit;
}

// ==========================================
// CALCULANDO MÉTRICAS (KPIs) PARA O DASHBOARD
// ==========================================
$totalAtivos = $conn->query("SELECT count(*) as c FROM vinhos WHERE status='disponivel'")->fetch_assoc()['c'];
$totalEsgotados = $conn->query("SELECT count(*) as c FROM vinhos WHERE status='esgotado'")->fetch_assoc()['c'];
$totalVisualizacoes = $conn->query("SELECT sum(visualizacoes) as v FROM vinhos")->fetch_assoc()['v'] ?? 0;

// ==========================================
// BUSCANDO PEDIDOS REAIS DO BANCO
// ==========================================
// Tenta buscar da tabela real. Caso a tabela ainda não exista, o PHP não travará o layout
$pedidosReais = $conn->query("SELECT * FROM pedidos ORDER BY id DESC");
$vendasHoje = 0;

if($pedidosReais) {
    $vendasHoje = $conn->query("SELECT count(*) as c FROM pedidos WHERE DATE(data_pedido) = CURDATE()")->fetch_assoc()['c'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin | Adega Select</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --wine-main: #630d0d;
            --wine-dark: #2c0808;
            --gold: #c5a028;
            --bg-body: #f4f7f6;
            --white: #ffffff;
        }

        body { background: var(--bg-body); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; color: #333; }
        
        .admin-header { background: var(--white); padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 100; }
        .admin-brand { color: var(--wine-dark); font-size: 1.5rem; font-weight: 800; display: flex; align-items: center; gap: 12px; }
        .admin-user { display: flex; align-items: center; gap: 20px; color: #555; font-weight: 600; }
        
        .btn-logout { background: #fee2e2; color: #dc2626; padding: 10px 20px; border-radius: 8px; text-decoration: none; transition: 0.3s; font-size: 0.9rem; font-weight: bold; }
        .btn-logout:hover { background: #fca5a5; transform: translateY(-2px); }

        .dashboard-container { max-width: 1240px; margin: 40px auto; padding: 0 25px; }

        /* KPI Cards */
        .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-bottom: 40px; }
        .kpi-card { background: var(--white); padding: 25px; border-radius: 15px; box-shadow: 0 10px 20px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 20px; border-left: 5px solid var(--wine-main); transition: 0.3s; }
        .kpi-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.05); }
        .kpi-icon { width: 60px; height: 60px; border-radius: 12px; background: #fdf2f2; color: var(--wine-main); display: flex; justify-content: center; align-items: center; font-size: 1.8rem; }
        .kpi-data h4 { margin: 0; color: #94a3b8; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; }
        .kpi-data span { font-size: 2rem; font-weight: 800; color: var(--wine-dark); }

        /* Tabs Navigation */
        .admin-tabs { display: flex; gap: 15px; margin-bottom: 30px; background: #e2e8f0; padding: 8px; border-radius: 12px; width: fit-content; }
        .tab-btn { border: none; padding: 12px 30px; cursor: pointer; font-weight: 700; color: #64748b; transition: 0.4s; border-radius: 10px; background: transparent; }
        .tab-btn.active { background: var(--white); color: var(--wine-main); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        
        .content-section { display: none; animation: fadeIn 0.5s ease; }
        .content-section.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* Tables and Wrappers */
        .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .table-wrapper { background: var(--white); border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); overflow: hidden; border: 1px solid #edf2f7; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8fafc; color: #475569; padding: 18px 25px; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; border-bottom: 2px solid #edf2f7; }
        td { padding: 20px 25px; border-bottom: 1px solid #edf2f7; vertical-align: middle; }
        
        .td-preco { font-weight: 800; color: var(--wine-main); font-size: 1.1rem; }
        .status-badge { padding: 6px 14px; border-radius: 8px; font-size: 0.75rem; font-weight: 800; }
        .status-preparando { background: #fffbeb; color: #b45309; }
        .status-entrega { background: #f0fdf4; color: #15803d; }

        /* Ações */
        .btn-action { width: 38px; height: 38px; border-radius: 10px; border: none; cursor: pointer; color: var(--white); transition: 0.3s; font-size: 1rem; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
        .btn-edit { background: #3b82f6; box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3); }
        .btn-delete { background: #f59e0b; box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3); }
        .btn-action:hover { transform: scale(1.1); filter: brightness(1.1); }

        /* MODAL PREMIUM STYLING */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(44, 8, 8, 0.6); backdrop-filter: blur(8px); z-index: 2000; justify-content: center; align-items: center; }
        .modal-content { background: var(--white); padding: 40px; border-radius: 24px; width: 90%; max-width: 500px; box-shadow: 0 25px 50px rgba(0,0,0,0.2); position: relative; }
        .modal-content h3 { margin-top: 0; color: var(--wine-dark); font-size: 1.6rem; display: flex; align-items: center; gap: 12px; margin-bottom: 30px; }
        
        .form-row { display: flex; gap: 20px; margin-bottom: 20px; }
        .form-group { flex: 1; text-align: left; }
        .form-group label { display: block; font-weight: 700; font-size: 0.85rem; color: #64748b; margin-bottom: 8px; text-transform: uppercase; }
        
        .modal-content input, .modal-content select { 
            width: 100%; padding: 14px 18px; border-radius: 12px; border: 2px solid #e2e8f0; 
            font-size: 1rem; font-family: inherit; transition: 0.3s; box-sizing: border-box; background: #f8fafc;
        }
        .modal-content input:focus { outline: none; border-color: var(--wine-main); background: var(--white); box-shadow: 0 0 0 4px rgba(99, 13, 13, 0.1); }

        .modal-actions { display: flex; gap: 15px; margin-top: 35px; }
        .btn-modal-cancel { flex: 1; padding: 16px; border-radius: 12px; border: none; background: #f1f5f9; color: #64748b; font-weight: 700; cursor: pointer; transition: 0.3s; }
        .btn-modal-save { flex: 2; padding: 16px; border-radius: 12px; border: none; background: linear-gradient(135deg, var(--wine-main), var(--wine-dark)); color: var(--white); font-weight: 700; cursor: pointer; box-shadow: 0 10px 20px rgba(99, 13, 13, 0.2); transition: 0.3s; }
        .btn-modal-save:hover { transform: translateY(-2px); box-shadow: 0 15px 30px rgba(99, 13, 13, 0.3); }
    </style>
</head>

<body>

    <header class="admin-header">
        <div class="admin-brand">
            <img src="img/adega-select.png" alt="Adega Select" style="height: 50px; width: auto;">
        </div>
        <div class="admin-user">
            <span><i class="fas fa-user-circle" style="color: var(--gold);"></i> <?= htmlspecialchars($_SESSION['user_nome'] ?? 'Admin') ?></span>
            <a href="logout.php" class="btn-logout">Sair <i class="fas fa-sign-out-alt"></i></a>
        </div>
    </header>

    <div class="dashboard-container">

        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-icon"><i class="fas fa-boxes-stacked"></i></div>
                <div class="kpi-data"><h4>Disponíveis</h4><span><?= $totalAtivos ?></span></div>
            </div>
            <div class="kpi-card" style="border-left-color: var(--gold);">
                <div class="kpi-icon" style="color: var(--gold);"><i class="fas fa-chart-line"></i></div>
                <div class="kpi-data"><h4>Cliques Vitrine</h4><span><?= $totalVisualizacoes ?></span></div>
            </div>
            <div class="kpi-card" style="border-left-color: #10b981;">
                <div class="kpi-icon" style="color: #10b981; background: #f0fdf4;"><i class="fas fa-receipt"></i></div>
                <div class="kpi-data"><h4>Vendas Hoje</h4><span><?= $vendasHoje ?></span></div>
            </div>
        </div>

        <div class="admin-tabs">
            <button class="tab-btn active" onclick="switchTab('stock')"><i class="fas fa-list-ul"></i> Catálogo</button>
            <button class="tab-btn" onclick="switchTab('orders')"><i class="fas fa-truck-fast"></i> Pedidos</button>
        </div>

        <section id="tab-stock" class="content-section active">
            <div class="table-header">
                <h2 style="font-size: 1.6rem;">Controle de Inventário</h2>
                <div style="display: flex; gap: 15px;">
                    <div style="position: relative;">
                        <i class="fas fa-search" style="position: absolute; left: 15px; top: 14px; color: #94a3b8;"></i>
                        <input type="text" id="searchInput" placeholder="Pesquisar rótulo..." onkeyup="filtrarTabela()" style="padding: 12px 12px 12px 45px; border-radius: 10px; border: 1px solid #cbd5e1; width: 300px; font-family: inherit;">
                    </div>
                    <button class="btn-modal-save" onclick="abrirModalAdd()" style="padding: 10px 25px; margin: 0; font-size: 0.9rem;"><i class="fas fa-plus"></i> Novo Vinho</button>
                </div>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Produto</th>
                            <th>Tipo / Safra</th>
                            <th>Preço</th>
                            <th style="text-align: center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tabela-vinhos-body">
                        <?php
                        $res = $conn->query("SELECT * FROM vinhos WHERE status='disponivel' ORDER BY id DESC");
                        while ($r = $res->fetch_assoc()):
                            $imgUrl = !empty($r['imagem_url']) ? htmlspecialchars($r['imagem_url']) : 'https://images.unsplash.com/photo-1584916201218-f4242ceb4809?auto=format&fit=crop&w=400&q=80';
                        ?>
                            <tr class="linha-vinho">
                                <td style="color: #94a3b8; font-weight: 800;">#<?= $r['id'] ?></td>
                                <td class="nome-vinho">
                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        <img src="<?= $imgUrl ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 10px; border: 1px solid #edf2f7;">
                                        <div style="font-weight: 700; color: var(--wine-dark);"><?= htmlspecialchars($r['nome']) ?></div>
                                    </div>
                                </td>
                                <td><span style="background: #f1f5f9; padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 700; color: #475569;"><?= $r['tipo'] ?> (<?= $r['safra'] ?>)</span></td>
                                <td class="td-preco">R$ <?= number_format($r['preco'], 2, ',', '.') ?></td>
                                <td>
                                    <div class="action-btns" style="justify-content: center;">
                                        <button onclick="abrirModalEdit(<?= $r['id'] ?>, <?= $r['preco'] ?>, this)" class="btn-action btn-edit" title="Editar Preço"><i class="fas fa-pen-to-square"></i></button>
                                        <button onclick="deletar(<?= $r['id'] ?>, this)" class="btn-action btn-delete" title="Marcar como Esgotado"><i class="fas fa-box-archive"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="tab-orders" class="content-section">
            <div class="table-header"><h2>Fluxo de Vendas Real (PIX)</h2></div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Nº Pedido</th>
                            <th>Cliente / Itens</th>
                            <th>Data</th>
                            <th>Valor Total</th>
                            <th>Status Logístico</th>
                            <th style="text-align: center;">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($pedidosReais): ?>
                            <?php while($p = $pedidosReais->fetch_assoc()): ?>
                            <tr>
                                <td style="font-weight: 800; color: #94a3b8;">#<?= $p['id'] ?></td>
                                <td style="font-weight: 700;">
                                    <?= htmlspecialchars($p['cliente_nome']) ?><br>
                                    <small style="font-weight: 400; color: #64748b;"><?= htmlspecialchars($p['itens_nomes']) ?></small>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($p['data_pedido'])) ?></td>
                                <td class="td-preco">R$ <?= number_format($p['valor_total'], 2, ',', '.') ?></td>
                                <td><span class="status-badge <?= $p['status_logistico'] == 'Entregue' ? 'status-entrega' : 'status-preparando' ?>"><?= $p['status_logistico'] ?></span></td>
                                <td>
                                    <div class="action-btns" style="justify-content: center;">
                                        <a href="https://www.mercadopago.com.br/activities/<?= $p['pagamento_id'] ?>" target="_blank" class="btn-action" style="background: #009ee3;" title="Conferir no Mercado Pago">
                                            <i class="fas fa-search-dollar"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align: center; padding: 50px;">Aguardando o primeiro pedido real...</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div id="modalAdd" class="modal-overlay">
        <div class="modal-content">
            <h3><i class="fas fa-cube" style="color: var(--gold);"></i> Minerar Novo Bloco</h3>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label>Rótulo / Nome Comercial</label>
                <input type="text" id="addNome" placeholder="Ex: Malbec Gran Reserva 2022">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Categoria</label>
                    <select id="addTipo">
                        <option value="Tinto">Tinto</option><option value="Branco">Branco</option><option value="Rosé">Rosé</option><option value="Espumante">Espumante</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Safra</label>
                    <input type="number" id="addSafra" placeholder="2024">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label>Preço de Venda (R$)</label>
                <input type="number" id="addPreco" step="0.01" placeholder="0.00">
            </div>

            <div class="form-group">
                <label>Link da Imagem (URL)</label>
                <input type="text" id="addImagem" placeholder="https://exemplo.com/vinho.jpg">
            </div>

            <div class="modal-actions">
                <button class="btn-modal-cancel" onclick="document.getElementById('modalAdd').style.display='none'">Cancelar</button>
                <button class="btn-modal-save" id="btn-salvar-novo" onclick="salvarNovoVinho()"><i class="fas fa-link"></i> Assinar e Minerar</button>
            </div>
        </div>
    </div>

    <div id="modalEdit" class="modal-overlay">
        <div class="modal-content">
            <h3><i class="fas fa-tags" style="color: var(--gold);"></i> Ajuste de Mercado</h3>
            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 25px;">A alteração de preço não rompe a integridade histórica do Blockchain.</p>
            
            <input type="hidden" id="editId">
            <div class="form-group">
                <label>Novo Valor Sugerido (R$)</label>
                <input type="number" id="editPreco" step="0.01">
            </div>

            <div class="modal-actions">
                <button class="btn-modal-cancel" onclick="document.getElementById('modalEdit').style.display='none'">Descartar</button>
                <button class="btn-modal-save" onclick="salvarEdicao()">Atualizar Bloco</button>
            </div>
        </div>
    </div>

    <script src="js/admin.js"></script>
    <script>
        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
            event.currentTarget.classList.add('active');
            document.getElementById('tab-' + tab).classList.add('active');
        }

        function filtrarTabela() {
            let termo = document.getElementById('searchInput').value.toLowerCase();
            document.querySelectorAll('.linha-vinho').forEach(linha => {
                let texto = linha.querySelector('.nome-vinho').textContent.toLowerCase();
                linha.style.display = texto.includes(termo) ? '' : 'none';
            });
        }
    </script>
    <?php include 'vlibras.php'; ?>
</body>
</html>