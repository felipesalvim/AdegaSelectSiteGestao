// Variável global para rastrear qual linha estamos editando no DOM
let linhaAtual = null;

// ==========================================
// 1. CONTROLES DE MODAL (ABRIR E FECHAR)
// ==========================================
function abrirModalEdit(id, preco, btnElement) {
    document.getElementById('editId').value = id;
    document.getElementById('editPreco').value = preco;
    linhaAtual = btnElement.closest('tr');

    // Usamos 'flex' porque no CSS o modal-overlay usa justify-content para centralizar
    document.getElementById('modalEdit').style.display = 'flex';
}

function abrirModalAdd() {
    // Limpa os campos sempre que abrir o modal de novo bloco
    document.getElementById('addNome').value = '';
    document.getElementById('addSafra').value = '';
    document.getElementById('addPreco').value = '';
    document.getElementById('addTipo').value = 'Tinto';

    document.getElementById('modalAdd').style.display = 'flex';
}

// ==========================================
// 2. CREATE (POST): MINERAR NOVO BLOCO
// ==========================================
async function salvarNovoVinho() {
    const nome = document.getElementById('addNome').value.trim();
    const tipo = document.getElementById('addTipo').value;
    const safra = document.getElementById('addSafra').value;
    const preco = document.getElementById('addPreco').value;
    // Captura a URL (se deixar vazio, mandamos uma string vazia para a API usar o padrão)
    const imagem = document.getElementById('addImagem').value.trim();
    const btnSalvar = document.getElementById('btn-salvar-novo');

    if (!nome || !safra || !preco) {
        alert("⚠️ Preencha os campos obrigatórios para minerar o bloco.");
        return;
    }

    btnSalvar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Minerando...';
    btnSalvar.disabled = true;

    try {
        const response = await fetch('api_vinhos.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                nome: nome,
                tipo: tipo,
                safra: parseInt(safra),
                preco: parseFloat(preco),
                imagem_url: imagem // Enviando o novo dado
            })
        });

        const data = await response.json();

        if (response.ok && data.status === "sucesso") {
            document.getElementById('modalAdd').style.display = 'none';
            alert("✅ Bloco selado com sucesso!");
            window.location.reload();
        } else {
            alert(data.erro || "Falha na mineração do bloco.");
        }
    } catch (error) {
        alert("Erro de comunicação com o servidor REST.");
    } finally {
        btnSalvar.innerHTML = '<i class="fas fa-hammer"></i> Minerar Bloco';
        btnSalvar.disabled = false;
    }
}

// ==========================================
// 3. UPDATE (PUT): ATUALIZAR PREÇO
// ==========================================
async function salvarEdicao() {
    const id = document.getElementById('editId').value;
    const novoPreco = document.getElementById('editPreco').value;

    if (!novoPreco || novoPreco <= 0) {
        alert("⚠️ Insira um preço válido.");
        return;
    }

    try {
        const response = await fetch('api_vinhos.php', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id, preco: parseFloat(novoPreco) })
        });

        const data = await response.json();

        if (response.ok && data.status === "sucesso") {
            // Atualiza a interface gráfica (DOM) sem recarregar a página!
            const precoFormatado = parseFloat(novoPreco).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            linhaAtual.querySelector('.td-preco').textContent = `R$ ${precoFormatado}`;

            // Efeito visual (pisca verde) para o usuário saber que funcionou
            linhaAtual.style.backgroundColor = '#d4edda';
            setTimeout(() => { linhaAtual.style.backgroundColor = ''; }, 1000);

            // Atualiza o botão para a próxima edição
            const btnEditar = linhaAtual.querySelector('.btn-edit');
            btnEditar.setAttribute('onclick', `abrirModalEdit(${id}, ${novoPreco}, this)`);

            document.getElementById('modalEdit').style.display = 'none';
        } else {
            alert(data.erro || "Erro ao atualizar no servidor.");
        }
    } catch (error) {
        alert("Erro de conexão com a API.");
    }
}

// ==========================================
// 4. DELETE (DELETE LOGICO): REMOVER DA VITRINE
// ==========================================
async function deletar(id, btnElement) {
    // Alerta focado na regra de negócio do Blockchain
    if (!confirm("Tem certeza? O vinho sairá da vitrine, mas o bloco continuará existindo no banco de dados para manter o encadeamento criptográfico da rede.")) return;

    try {
        const response = await fetch('api_vinhos.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        });

        const data = await response.json();

        if (response.ok && data.status === "sucesso") {
            // Manipulação de DOM para remover a linha com animação suave
            const linhaTabela = btnElement.closest('tr');
            linhaTabela.style.transition = "all 0.4s ease";
            linhaTabela.style.opacity = "0";
            linhaTabela.style.transform = "translateX(-30px)";

            setTimeout(() => {
                linhaTabela.remove();
                // Atualiza a página para reprocessar os KPIs no topo
                window.location.reload();
            }, 400);
        } else {
            alert(data.erro || "Erro ao processar exclusão.");
        }
    } catch (error) {
        alert("Erro ao conectar com o servidor.");
    }
}