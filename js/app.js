// ==========================================
// ESTADO GLOBAL DA APLICAÇÃO
// ==========================================
let vinhosGlobais = [];
let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
let filtroAtualTipo = 'Todos';
let filtroAtualPreco = 'Todos';

// ==========================================
// INICIALIZAÇÃO
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    atualizarBadgeCarrinho();

    // Se estiver na página inicial (Vitrine)
    if (document.getElementById('grid-vinhos')) {
        carregarVinhos();
        // Inicia o chatbot silenciosamente para não perder a sessão
        fetch('chatbot.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: 'init' })
        });
    }

    // Se estiver na página de Checkout
    if (document.getElementById('tabela-checkout-body')) {
        renderizarCarrinhoCheckout();
    }
});

// ==========================================
// 1. VITRINE E FILTROS
// ==========================================
async function carregarVinhos() {
    try {
        const response = await fetch('api_vinhos.php');
        const data = await response.json();
        
        if (data.status === 'sucesso') {
            vinhosGlobais = data.dados;
            renderizarVinhos(vinhosGlobais);
        }
    } catch (error) {
        console.error("Erro ao carregar catálogo:", error);
        document.getElementById('grid-vinhos').innerHTML = '<p style="text-align:center; width:100%;">Erro ao carregar a vitrine. Tente novamente.</p>';
    }
}

function renderizarVinhos(lista) {
    const grid = document.getElementById('grid-vinhos');
    const template = document.getElementById('wine-card-template');
    
    grid.innerHTML = '';

    if (lista.length === 0) {
        grid.innerHTML = '<p style="text-align:center; width:100%; color:#666;">Nenhum vinho encontrado para este filtro.</p>';
        return;
    }

    lista.forEach(v => {
        const clone = template.content.cloneNode(true);
        
        clone.querySelector('.wine-image').src = v.imagem_url || 'https://via.placeholder.com/400x400?text=Vinho';
        clone.querySelector('.js-tipo').textContent = v.tipo;
        clone.querySelector('.js-safra').textContent = v.safra;
        clone.querySelector('.js-nome').textContent = v.nome;
        
        // Formatação de Preço BRL
        const precoFormatado = parseFloat(v.preco).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        clone.querySelector('.js-preco').textContent = precoFormatado;
        
        clone.querySelector('.js-hash').textContent = v.hash_blockchain ? v.hash_blockchain.substring(0, 16) + '...' : 'Pendente';
        clone.querySelector('.js-link-rastrear').href = `detalhes.php?id=${v.id}`;
        
        // Evento de Comprar
        const btnComprar = clone.querySelector('.js-btn-comprar');
        btnComprar.onclick = () => adicionarAoCarrinho(v.id, v.nome, parseFloat(v.preco));

        grid.appendChild(clone);
    });
}

window.aplicarFiltros = function(btnElement, categoria) {
    // Atualiza o visual dos botões
    const parent = btnElement.parentElement;
    parent.querySelectorAll('.btn-filter').forEach(b => b.classList.remove('active'));
    btnElement.classList.add('active');

    // Define os estados
    if (categoria === 'tipo') {
        filtroAtualTipo = btnElement.getAttribute('data-type');
    } else if (categoria === 'preco') {
        filtroAtualPreco = btnElement.getAttribute('data-price');
    }

    // Aplica a lógica de filtragem cruzada
    const vinhosFiltrados = vinhosGlobais.filter(v => {
        const preco = parseFloat(v.preco);
        let passaTipo = (filtroAtualTipo === 'Todos' || v.tipo === filtroAtualTipo);
        let passaPreco = true;

        if (filtroAtualPreco === '0-100') passaPreco = (preco <= 100);
        else if (filtroAtualPreco === '100-200') passaPreco = (preco > 100 && preco <= 200);
        else if (filtroAtualPreco === '200-9999') passaPreco = (preco > 200);

        return passaTipo && passaPreco;
    });

    renderizarVinhos(vinhosFiltrados);
}

// ==========================================
// 2. CARRINHO DE COMPRAS (LOCAL STORAGE)
// ==========================================
function adicionarAoCarrinho(id, nome, preco) {
    // Verifica se já existe para aumentar a quantidade (Opcional, aqui vamos empilhar simples)
    const itemExistente = carrinho.find(i => i.id === id);
    if (itemExistente) {
        itemExistente.quantidade += 1;
    } else {
        carrinho.push({ id, nome, preco, quantidade: 1 });
    }
    
    salvarCarrinho();
    atualizarBadgeCarrinho();
    
    alert(`🍷 ${nome} adicionado ao carrinho!`);
}

function salvarCarrinho() {
    localStorage.setItem('carrinho', JSON.stringify(carrinho));
}

function atualizarBadgeCarrinho() {
    const badge = document.getElementById('cart-count');
    if (badge) {
        const totalItens = carrinho.reduce((acc, item) => acc + item.quantidade, 0);
        badge.textContent = totalItens;
        
        // Pequena animação
        badge.style.transform = 'scale(1.3)';
        setTimeout(() => badge.style.transform = 'scale(1)', 200);
    }
}

// Lógica Exclusiva da página Checkout.php
window.renderizarCarrinhoCheckout = function() {
    const tbody = document.getElementById('tabela-checkout-body');
    const totalBox = document.getElementById('checkout-total');
    
    tbody.innerHTML = '';
    let valorTotal = 0;

    if (carrinho.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;">Seu carrinho está vazio. <a href="index.php">Voltar à loja</a></td></tr>';
        totalBox.textContent = 'R$ 0,00';
        return;
    }

    carrinho.forEach((item, index) => {
        const subtotal = item.preco * item.quantidade;
        valorTotal += subtotal;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="item-nome">${item.nome}</td>
            <td>${item.quantidade}x</td>
            <td class="item-preco">${subtotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}</td>
            <td><button class="btn-remove" onclick="removerItem(${index})"><i class="fas fa-trash"></i></button></td>
        `;
        tbody.appendChild(tr);
    });

    totalBox.textContent = valorTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}

window.removerItem = function(index) {
    carrinho.splice(index, 1);
    salvarCarrinho();
    renderizarCarrinhoCheckout();
    atualizarBadgeCarrinho();
}

// ==========================================
// 3. CHATBOT (SOMMELIER IA)
// ==========================================
window.toggleChat = function() {
    const chat = document.getElementById('chat-container');
    chat.classList.toggle('chat-minimized');
    
    // Se abrir pela primeira vez e estiver vazio, dá as boas vindas
    const msgs = document.getElementById('chat-msgs');
    if (!chat.classList.contains('chat-minimized') && msgs.innerHTML === '') {
        adicionarMensagemChat('Robo', 'Olá! Sou o Sommelier Virtual da <b>Adega Select</b>. 🍷<br>Para eu te dar um atendimento personalizado, qual é o seu <b>Nome</b>?');
    }
}

window.enviarParaIA = async function(mensagemDireta = null) {
    const input = document.getElementById('chat-input');
    const texto = mensagemDireta !== null ? mensagemDireta : input.value.trim();
    
    if (texto === '') return;

    // Mostra mensagem do usuário
    adicionarMensagemChat('Usuario', texto);
    if (mensagemDireta === null) input.value = ''; // Limpa input só se não for botão de sugestão

    // Mostra "Digitando..."
    const typingId = adicionarMensagemChat('Robo', '<i class="fas fa-ellipsis-h fa-fade"></i>');

    try {
        const response = await fetch('chatbot.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: texto })
        });
        
        const data = await response.json();
        
        // Remove "Digitando..." e insere a resposta real
        document.getElementById(typingId).remove();
        adicionarMensagemChat('Robo', data.answer);
        
    } catch (error) {
        document.getElementById(typingId).remove();
        adicionarMensagemChat('Robo', 'Desculpe, minha conexão com a adega falhou. Pode repetir?');
    }
}

window.enviarSugestao = function(texto) {
    enviarParaIA(texto);
}

function adicionarMensagemChat(remetente, textoHTML) {
    const msgsDiv = document.getElementById('chat-msgs');
    const msgId = 'msg-' + Date.now();
    
    const div = document.createElement('div');
    div.id = msgId;
    div.className = remetente === 'Usuario' ? 'chat-msg user-msg' : 'chat-msg bot-msg';
    div.innerHTML = textoHTML;
    
    msgsDiv.appendChild(div);
    msgsDiv.scrollTop = msgsDiv.scrollHeight; // Rola para o fim
    
    return msgId;
}