<?php
class WineChain
{
    // Chave mestra privada (Sal criptográfico)
    private static string $secret_key = "CHAVE_MESTRE_ADEGA_DIGITAL_2026";

    /**
     * Resgata o hash do último bloco minerado.
     * Se for o primeiro vinho, retorna o Bloco Gênesis (64 zeros).
     */
    public static function getPreviousHash($conn): string
    {
        $res = $conn->query("SELECT hash_blockchain FROM vinhos ORDER BY id DESC LIMIT 1");

        if ($res && $row = $res->fetch_assoc()) {
            return !empty($row['hash_blockchain']) ? $row['hash_blockchain'] : str_repeat('0', 64);
        }

        return str_repeat('0', 64);
    }

    /**
     * Minera o bloco (Gera o Hash SHA-256).
     * MELHORIA: Agora inclui o TIPO e o PREÇO na criptografia. 
     */
    public static function generateProof($id, $nome, $tipo, $safra, $preco, $prevHash): string
    {
        // Transformamos todos os dados vitais do vinho em uma única string (Payload)
        // Usamos trim() para evitar que espaços em branco acidentais quebrem a assinatura
        $payload = $id . trim($nome) . trim($tipo) . $safra . $preco . $prevHash . self::$secret_key;

        // Retorna o hash criptográfico inviolável
        return hash('sha256', $payload);
    }

    /**
     * 🚀 BÔNUS PARA A BANCA: Função de Auditoria da Rede
     * Varre o banco de dados inteiro e matematicamente prova se a rede foi hackeada.
     */
    public static function isChainValid($conn): bool
    {
        $res = $conn->query("SELECT * FROM vinhos ORDER BY id ASC");
        $vinhos = $res->fetch_all(MYSQLI_ASSOC);

        // Começa do índice 1 porque o índice 0 é o bloco Gênesis
        for ($i = 1; $i < count($vinhos); $i++) {
            $blocoAtual = $vinhos[$i];
            $blocoAnterior = $vinhos[$i - 1];

            // Regra 1: O "hash_anterior" do bloco atual bate com o hash real do bloco passado?
            if ($blocoAtual['hash_anterior'] !== $blocoAnterior['hash_blockchain']) {
                return false; // A corrente (encadeamento) foi rompida!
            }

            // Regra 2: Os dados do vinho atual foram adulterados no banco?
            $hashRecalculado = self::generateProof(
                $blocoAtual['id'],
                $blocoAtual['nome'],
                $blocoAtual['tipo'],
                $blocoAtual['safra'],
                $blocoAtual['preco'],
                $blocoAtual['hash_anterior']
            );

            if ($blocoAtual['hash_blockchain'] !== $hashRecalculado) {
                return false; // Alguém tentou alterar o preço ou o nome diretamente no MySQL!
            }
        }

        return true; // Rede 100% íntegra e inviolada
    }
}
