<?php
// Define que a resposta será no formato JSON (padrão que o Android espera)
header('Content-Type: application/json');

// 1. SUA CREDENCIAL DE PRODUÇÃO (A mesma do processar_pagamento.php)
$access_token = 'APP_USR-5589165249009523-021920-2a6b6da31110cd96cb684665123608fc-214740245'; 

// 2. CAPTURA O ID ENVIADO PELO ANDROID
// O Android envia via GET: consultar_status.php?id=123456789
if (!isset($_GET['id']) || empty($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['status_pagamento' => 'error', 'message' => 'ID do pagamento não informado.']);
    exit;
}

// Limpa o ID para garantir que seja apenas números (Segurança básica)
$payment_id = preg_replace('/[^0-9]/', '', $_GET['id']);

// 3. CONSULTA DIRETAMENTE NO MERCADO PAGO
$url = "https://api.mercadopago.com/v1/payments/" . $payment_id;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Evita erro de certificado
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $access_token
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 4. PROCESSA A RESPOSTA E DEVOLVE PARA O ANDROID
if ($http_code == 200) {
    $res_data = json_decode($response, true);
    
    // O Mercado Pago retorna o status (pending, approved, rejected, etc)
    $status_atual = $res_data['status'] ?? 'unknown';
    
    // Devolvemos exatamente a chave que o Android está esperando ler
    echo json_encode([
        'status_pagamento' => $status_atual
    ]);
} else {
    // Se der erro na consulta, devolvemos como pendente para o Android continuar tentando
    echo json_encode([
        'status_pagamento' => 'pending',
        'message' => 'Falha ao consultar a API do Mercado Pago.'
    ]);
}
?>