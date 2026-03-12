<?php
header('Content-Type: application/json');

// 1. SUA CREDENCIAL DE PRODUÇÃO
$access_token = 'APP_USR-5589165249009523-021920-2a6b6da31110cd96cb684665123608fc-214740245'; 

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Dados inválidos. JSON vazio.']);
    exit;
}

// 2. CALCULAR TOTAL DO CARRINHO
$total_pagar = 0;

// O Android/Web agora envia o valor final no campo "total"
if (isset($data['total']) && (float)$data['total'] > 0) {
    $total_pagar = (float) $data['total'];
} elseif (!empty($data['carrinho'])) {
    foreach ($data['carrinho'] as $item) {
        // Aceita 'unit_price', 'preco' ou 'price' para compatibilidade total
        $preco = $item['unit_price'] ?? ($item['preco'] ?? ($item['price'] ?? 0));
        $qtd = $item['quantidade'] ?? ($item['quantity'] ?? 1);
        $total_pagar += ((float)$preco * (int)$qtd);
    }
}

// Trava de segurança no próprio servidor
if ($total_pagar <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Valor total zerado.']);
    exit;
}

// 3. MONTAR O PAYLOAD PARA O PIX DIRETO NO MERCADO PAGO
$payment_data = [
    "transaction_amount" => $total_pagar,
    "description" => "Pedido Adega Select",
    "payment_method_id" => "pix",
    "payer" => [
        "email" => $data['email'],
        "first_name" => $data['nome'],
        "identification" => [
            "type" => "CPF",
            "number" => preg_replace('/[^0-9]/', '', $data['cpf']) // Limpa pontuação
        ]
    ]
];

// 4. CHAMAR A API DIRETA DE PAGAMENTOS
$ch = curl_init('https://api.mercadopago.com/v1/payments');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payment_data));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $access_token,
    'Content-Type: application/json',
    'X-Idempotency-Key: ' . uniqid() 
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$res_data = json_decode($response, true);

// 5. PROCESSAR RESPOSTA E GRAVAR PEDIDO REAL NO BANCO
if ($http_code == 201 || $http_code == 200) {
    
    // Importa a conexão para gravar na tabela real de pedidos
    include 'config.php';
    
    $pagamento_id = $res_data['id'];
    $nome = $data['nome'];
    $email = $data['email'];
    $cpf = preg_replace('/[^0-9]/', '', $data['cpf']);
    
    // Constrói a lista de itens para o administrador ver o que foi pedido
    $itens_lista = [];
    if (!empty($data['carrinho'])) {
        foreach ($data['carrinho'] as $item) {
            $itens_lista[] = ($item['quantidade'] ?? 1) . "x " . $item['nome'];
        }
    }
    $itens_str = !empty($itens_lista) ? implode(", ", $itens_lista) : "Itens não especificados";

    try {
        // Insere o pedido na tabela real 'pedidos' que criamos
        $stmt = $conn->prepare("INSERT INTO pedidos (pagamento_id, cliente_nome, cliente_email, cliente_cpf, valor_total, itens_nomes, status_logistico) VALUES (?, ?, ?, ?, ?, ?, 'Preparando')");
        $stmt->bind_param("ssssds", $pagamento_id, $nome, $email, $cpf, $total_pagar, $itens_str);
        $stmt->execute();
    } catch (Exception $e) {
        // Apenas loga o erro de banco para não travar o pagamento do cliente
        error_log("Erro ao gravar pedido no banco: " . $e->getMessage());
    }

    // Retorna os dados para o Front-end exibir o QR Code
    echo json_encode([
        'status' => 'success',
        'payment_id' => $pagamento_id, 
        'qr_code_base64' => $res_data['point_of_interaction']['transaction_data']['qr_code_base64'],
        'qr_code_texto' => $res_data['point_of_interaction']['transaction_data']['qr_code']
    ]);

} else {
    http_response_code(500);
    echo json_encode([
        'status' => 'error', 
        'message' => 'Erro ao gerar PIX: ' . ($res_data['message'] ?? 'Desconhecido')
    ]);
}
?>