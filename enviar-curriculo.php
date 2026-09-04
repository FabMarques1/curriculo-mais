<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once("config/database.php");

$nome = $_POST['nome'];
$sobrenome = $_POST['sobrenome'];
$email = $_POST['email'];
$cidade = $_POST['cidade'];
$resumoProfissional = $_POST['resumoProfissional'];
$curriculo = $_FILES['curriculo'];

try{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($curriculo) && $curriculo['error'] === UPLOAD_ERR_OK) {
            
        $pastaDestino = 'curriculos/';

        if (!is_dir($pastaDestino)) {
            mkdir($pastaDestino, 0755, true);
        }

        $nomeOriginal = $curriculo['name'];
        $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
        $caminhoTemp = $curriculo['tmp_name'];
        $tamanho = $curriculo['size'];

        $tamanhoMax = 2 * 1024 * 1024;

        if ($extensao !== 'pdf') {
            die("Erro: Apenas arquivos com a extensão .PDF são permitidos.");
        }

        $tipoMime = mime_content_type($caminhoTemp);
        if ($tipoMime !== 'application/pdf') {
            die("Erro: O conteúdo do arquivo não é um PDF válido.");
        }

        if ($tamanho > $tamanhoMax) {
            die("Erro: O arquivo excede o limite máximo permitido de 2 MB.");
        }

        $hash16       = bin2hex(random_bytes(8));
        $novoNome     = $hash16 . '.pdf';
        $caminhoFinal = $pastaDestino . $novoNome;

        if (move_uploaded_file($caminhoTemp, $caminhoFinal)) {
            
            $sql = "INSERT INTO tbl_usuario (nome, sobrenome, email, resumo_profissional, curriculo, id_cidade) VALUES
                    (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "sssssi", 
                $nome, 
                $sobrenome, 
                $email, 
                $resumoProfissional,
                $caminhoFinal,
                $cidade
            );

            if ($stmt->execute()) {
                # $_SESSION['aviso'] = "Currículo enviado com sucesso!";
                header("Location: index.php");
            } else {
                echo "Erro ao salvar informações no banco de dados.";
            }

            $stmt->close();

        } else {
            echo "Erro ao mover o arquivo para a pasta de destino.";
        }

    } else {
        echo "Por favor, selecione um arquivo válido.";
    }
} catch (Exception $e) {
    echo "Erro ao enviar o seu currículo: " . $e->getMessage();
}

$conn->close();

?>