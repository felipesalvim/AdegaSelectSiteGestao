<?php
header("Content-Type: application/json; charset=UTF-8");
include 'config.php';
include 'blockchain.php';

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"));

switch ($method) {
    case 'GET':
        $res = $conn->query("SELECT * FROM vinhos WHERE status = 'disponivel' ORDER BY id DESC");
        $vinhos = [];
        while ($r = $res->fetch_assoc()) {
            $vinhos[] = $r;
        }
        echo json_encode(["status" => "sucesso", "dados" => $vinhos]);
        break;

    case 'POST':
        // Blindagem 1: Verifica se os dados vitais chegaram
        if (!isset($data->nome) || !isset($data->tipo) || !isset($data->safra) || !isset($data->preco)) {
            http_response_code(400);
            echo json_encode(["erro" => "Dados incompletos para minerar o bloco."]);
            exit;
        }

        $conn->begin_transaction();
        try {
            // CORREÇÃO SÊNIOR: Pega o hash do bloco anterior ANTES de inserir a nova linha!
            $hashAnt = WineChain::getPreviousHash($conn);

            // Se o usuário não mandou imagem, usa a foto padrão genérica
            $urlFoto = !empty($data->imagem_url) ? $data->imagem_url : 'https://images.unsplash.com/photo-1584916201218-f4242ceb4809?auto=format&fit=crop&w=400&q=80';

            // Insere o vinho já gravando o bloco pai (hash_anterior) e a URL da Imagem
            $stmt = $conn->prepare("INSERT INTO vinhos (nome, tipo, safra, preco, hash_anterior, imagem_url) VALUES (?, ?, ?, ?, ?, ?)");
            // Tipos: s (string), s (string), i (integer), d (double), s (string), s (string)
            $stmt->bind_param("ssidss", $data->nome, $data->tipo, $data->safra, $data->preco, $hashAnt, $urlFoto);
            $stmt->execute();

            // Pega o ID que o MySQL acabou de gerar
            $novoId = $conn->insert_id;

            // Gera a assinatura inviolável do bloco atual
            $hashAtual = WineChain::generateProof($novoId, $data->nome, $data->tipo, $data->safra, $data->preco, $hashAnt);

            // Atualiza apenas a assinatura final do bloco
            $upd = $conn->prepare("UPDATE vinhos SET hash_blockchain=? WHERE id=?");
            $upd->bind_param("si", $hashAtual, $novoId);
            $upd->execute();

            $conn->commit();
            http_response_code(201);
            echo json_encode(["status" => "sucesso", "mensagem" => "Bloco minerado com sucesso."]);
        } catch (Exception $e) {
            $conn->rollback();
            http_response_code(500);
            echo json_encode(["erro" => "Falha na mineração: " . $e->getMessage()]);
        }
        break;

    case 'PUT':
        if (!isset($data->id) || !isset($data->preco)) {
            http_response_code(400);
            echo json_encode(["erro" => "ID e Preço são obrigatórios."]);
            exit;
        }

        $stmt = $conn->prepare("UPDATE vinhos SET preco = ? WHERE id = ?");
        $stmt->bind_param("di", $data->preco, $data->id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "sucesso"]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Falha ao atualizar o banco de dados."]);
        }
        break;

    case 'DELETE':
        if (!isset($data->id)) {
            http_response_code(400);
            echo json_encode(["erro" => "ID é obrigatório para exclusão."]);
            exit;
        }

        $stmt = $conn->prepare("UPDATE vinhos SET status = 'esgotado' WHERE id = ?");
        $stmt->bind_param("i", $data->id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "sucesso"]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Falha ao excluir o registro."]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["erro" => "Método não suportado."]);
        break;
}
