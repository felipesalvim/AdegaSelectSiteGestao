<?php
// Inicia a sessão para dar "memória" ao Chatbot
session_start();
header("Content-Type: application/json; charset=UTF-8");

$input = json_decode(file_get_contents('php://input'), true);
$perguntaRaw = isset($input['message']) ? trim($input['message']) : '';
$pergunta = strtolower($perguntaRaw);

// ==========================================
// 1. CONFIGURAÇÕES INICIAIS
// ==========================================
// Número do WhatsApp da sua empresa (Coloque o número real com DDD)
$numero_whatsapp = "5585998261414"; 

// Reset de memória quando o JS manda 'init' ao abrir a tela ou quando o usuário pede
if ($pergunta === 'init' || $pergunta === 'reset' || $pergunta === 'reiniciar' || $pergunta === 'oi' || $pergunta === 'olá') {
    $_SESSION['chat_state'] = 0;
    $_SESSION['user_data'] = [];
    $_SESSION['chat_history'] = [];
}

// Botão padrão de voltar
$btnVoltar = "<br><br><button onclick=\"enviarSugestao('Menu')\" style='background:#fff; border:1px solid var(--gold); color:var(--wine-dark); padding:5px 10px; border-radius:15px; cursor:pointer; font-size:0.8rem; margin-top:10px;'><i class='fas fa-undo'></i> Voltar ao Menu</button>";

$resposta = "";

// Registra as interações no histórico para o WhatsApp (se já passou da fase de cadastro)
// Ignora 'init' e 'menu' para não poluir o histórico
if (isset($_SESSION['chat_state']) && $_SESSION['chat_state'] >= 3 && $pergunta !== 'menu' && $pergunta !== 'init') {
    $_SESSION['chat_history'][] = "• Cliente explorou: " . ucfirst($perguntaRaw);
}

// ==========================================
// 2. MÁQUINA DE ESTADOS (FUNIL DE CHAT)
// ==========================================

// Se não tiver estado definido, começa do zero
if (!isset($_SESSION['chat_state'])) {
    $_SESSION['chat_state'] = 0;
}

// Se o usuário pedir o menu, pula direto para o estado 2 (se já tiver nome)
if ($pergunta === 'menu' || $pergunta === 'voltar') {
    if (!empty($_SESSION['user_data']['nome'])) {
        $_SESSION['chat_state'] = 2; // Menu principal
    } else {
        $_SESSION['chat_state'] = 0; // Pede o nome de novo se der erro
    }
}

switch ($_SESSION['chat_state']) {

    // ESTADO 0: Mensagem de Boas-Vindas
    case 0:
        $resposta = "Olá! Sou o Sommelier Virtual da <b>Adega Select</b>. 🍷<br>Para eu te dar um atendimento personalizado, qual é o seu <b>Nome</b>?";
        $_SESSION['chat_state'] = 1; 
        break;

    // ESTADO 1: Recebe o Nome e Pede o WhatsApp
    case 1:
        if ($pergunta === 'init') {
            $resposta = "Olá! Sou o Sommelier Virtual da <b>Adega Select</b>. 🍷<br>Para eu te dar um atendimento personalizado, qual é o seu <b>Nome</b>?";
        } else {
            $_SESSION['user_data']['nome'] = $perguntaRaw; 
            $resposta = "Muito prazer, <b>" . htmlspecialchars($perguntaRaw) . "</b>! 😊<br>Qual é o seu número de <b>WhatsApp</b> (com DDD)? Prometo não mandar spam, é só para um especialista te chamar se você precisar fechar uma compra.";
            $_SESSION['chat_state'] = 2; 
        }
        break;

    // ESTADO 2: Recebe o WhatsApp e Mostra o Menu Expandido
    case 2:
        if (empty($_SESSION['user_data']['whatsapp']) && $pergunta !== 'menu' && $pergunta !== 'init') {
            $_SESSION['user_data']['whatsapp'] = $perguntaRaw;
        }

        $nome = $_SESSION['user_data']['nome'] ?? 'Visitante';
        $resposta = "Perfeito, $nome! Como posso te ajudar hoje? Selecione uma das opções abaixo:<br><br>";

        // Botões de navegação rápida
        $estiloBtn = "background:var(--wine-main); color:white; border:none; padding:8px 12px; border-radius:8px; margin-bottom:8px; cursor:pointer; width:100%; text-align:left; font-size:0.85rem;";

        $resposta .= "<button onclick=\"enviarSugestao('Carnes')\" style='$estiloBtn'>🥩 1. Harmonização p/ Carnes</button><br>";
        $resposta .= "<button onclick=\"enviarSugestao('Peixes')\" style='$estiloBtn'>🐟 2. Harmonização p/ Peixes</button><br>";
        $resposta .= "<button onclick=\"enviarSugestao('Massas')\" style='$estiloBtn'>🍝 3. Harmonização p/ Massas e Queijos</button><br>";
        $resposta .= "<button onclick=\"enviarSugestao('Presente')\" style='$estiloBtn'>🎁 4. Sugestões para Presente</button><br>";
        $resposta .= "<button onclick=\"enviarSugestao('Blockchain')\" style='$estiloBtn'>🔗 5. Como funciona o Blockchain</button><br>";
        $resposta .= "<button onclick=\"enviarSugestao('Especialista')\" style='background:#25D366; color:white; border:none; padding:8px 12px; border-radius:8px; cursor:pointer; width:100%; text-align:left; font-weight:bold;'><i class='fab fa-whatsapp'></i> 6. Falar com Especialista Humano</button>";

        $_SESSION['chat_state'] = 3; 
        break;

    // ESTADO 3: Motor de Respostas (Loop de conversa)
    case 3:
        if (strpos($pergunta, 'carne') !== false || $pergunta == '1') {
            $resposta = "🥩 <b>Carnes Vermelhas</b> pedem vinhos encorpados e com taninos fortes para quebrar a gordura do prato. <br><br>Recomendo fortemente nosso <b>Malbec Argentino Clássico</b> (ótimo para churrasco) ou o <b>Cabernet Sauvignon Reserva</b>.<br><br>Deseja ver opções para peixes ou falar com nosso Sommelier Humano para fechar um pedido?" . $btnVoltar;
            $_SESSION['chat_history'][] = "-> A IA recomendou Malbec/Cabernet.";
            
        } elseif (strpos($pergunta, 'peixe') !== false || $pergunta == '2') {
            $resposta = "🐟 <b>Peixes e Frutos do Mar</b> exigem delicadeza. Vinhos brancos com boa acidez limpam o paladar. <br><br>Nosso <b>Chardonnay Premium Vale</b> é a escolha ideal para não ofuscar o sabor da refeição.<br><br>Gostaria de falar com o especialista para confirmar a disponibilidade de safras?" . $btnVoltar;
            $_SESSION['chat_history'][] = "-> A IA recomendou Chardonnay.";
            
        } elseif (strpos($pergunta, 'massa') !== false || strpos($pergunta, 'queijo') !== false || $pergunta == '3') {
            $resposta = "🍝 <b>Massas e Queijos</b> são clássicos que pedem bons vinhos! Molhos vermelhos combinam com tintos médios, enquanto queijos pedem brancos encorpados ou tintos macios. <br><br>Sugerimos o nosso <b>Merlot Selection Especial</b> ou o suave <b>Pinot Noir Gran Reserva</b>.<br><br>Podemos preparar essa garrafa para você?" . $btnVoltar;
            $_SESSION['chat_history'][] = "-> A IA recomendou Merlot/Pinot Noir para Massas.";
            
        } elseif (strpos($pergunta, 'presente') !== false || $pergunta == '4') {
            $resposta = "🎁 <b>Vinhos para Presente</b> são escolhas inesquecíveis! <br><br>Para impressionar, recomendamos celebrar com o <b>Espumante Brut Imperial</b>, ou dar um presente de classe com o <b>Syrah Encorpado Ouro</b>. O mais legal é que seu presenteado poderá rastrear a origem da garrafa via Blockchain!<br><br>Quer falar com um humano para montar um kit personalizado?" . $btnVoltar;
            $_SESSION['chat_history'][] = "-> A IA sugeriu Espumante/Syrah para Presente.";
            
        } elseif (strpos($pergunta, 'blockchain') !== false || $pergunta == '5') {
            $resposta = "🔗 <b>Nossa Tecnologia Blockchain</b><br>Cada garrafa da nossa adega recebe uma assinatura digital imutável (Hash SHA-256) no momento da compra. Isso cria um certificado de autenticidade que garante que o vinho nunca foi adulterado ou falsificado. Você compra segurança!" . $btnVoltar;
            $_SESSION['chat_history'][] = "-> A IA explicou sobre Blockchain.";
            
        } elseif (strpos($pergunta, 'especialista') !== false || strpos($pergunta, 'humano') !== false || $pergunta == '6') {

            // CONSTRUÇÃO DO LINK DO WHATSAPP
            $nome = $_SESSION['user_data']['nome'] ?? 'Cliente';
            $tel = $_SESSION['user_data']['whatsapp'] ?? 'Não informado';

            $historico_str = implode("%0A", $_SESSION['chat_history']);
            if (empty($historico_str)) {
                $historico_str = "Nenhuma navegação específica prévia.";
            }

            // O Markdown (*) é mantido aqui porque o aplicativo do WhatsApp interpreta asteriscos nativamente.
            $msg_zap = "Olá, Especialista! 👋%0AMeu nome é *$nome* (Tel: $tel) e vim pelo atendimento da Inteligência Artificial do site.%0A%0A*Meu histórico de interesse:*%0A$historico_str%0A%0AGostaria de finalizar meu pedido com a Adega Select!";

            $link_whatsapp = "https://wa.me/$numero_whatsapp?text=" . $msg_zap;

            $resposta = "Excelente, $nome! É sempre bom falar com um especialista para as melhores safras.<br><br>Clique no botão abaixo para ser redirecionado para o WhatsApp com todo o seu histórico já preenchido:<br><br>";
            $resposta .= "<a href='$link_whatsapp' target='_blank' style='display:inline-block; background:#25D366; color:white; padding:12px; border-radius:8px; text-decoration:none; font-weight:bold; font-size:1rem;'><i class='fab fa-whatsapp'></i> Abrir WhatsApp Agora</a>";
            $resposta .= $btnVoltar;
            
        } else {
            // Fallback: Se a IA não entender, orienta graciosamente
            $resposta = "Desculpe, meu conhecimento como Sommelier Virtual ainda está aprendendo essa uva. 🍇<br>Digite <b>Menu</b> para ver minhas categorias de especialidade ou escolha um número de 1 a 6." . $btnVoltar;
        }
        break;
}

// 3. Devolve a resposta formatada
echo json_encode(['answer' => $resposta]);
?>