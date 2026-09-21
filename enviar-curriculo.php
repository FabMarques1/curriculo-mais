<?php
session_start();

require_once("config/database.php");

$idUsuario = $_SESSION['id'];
$resumoProfissional = $_POST['resumoProfissional'];
$curriculo = $_FILES['curriculo'];
$idVaga = $_POST['vaga'];

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

        $mimesPermitidos = [
            'pdf'  => 'application/pdf'
        ];

        if (!array_key_exists($extensao, $mimesPermitidos)) {
            header("Location: error.php?error=103");
            exit;
        }

        $tipoMime = mime_content_type($caminhoTemp);
        if ($tipoMime !== $mimesPermitidos[$extensao]) {
            header("Location: error.php?error=103");
            exit;
        }

        if ($tamanho > $tamanhoMax) {
            header("Location: error.php?error=104");
            exit;
        }

        $hash16       = bin2hex(random_bytes(8));
        $novoNome     = $hash16 . '.' . $extensao;
        $caminhoFinal = $pastaDestino . $novoNome;

        if (move_uploaded_file($caminhoTemp, $caminhoFinal)) {
            
            $sql = "INSERT INTO tbl_curriculo (resumo_profissional, curriculo, id_usuario, id_vaga) VALUES
                    (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssii", $resumoProfissional, $caminhoFinal, $idUsuario, $idVaga);

            if ($stmt->execute()) {
                header("Location: index.php");
            } else {
                header("Location: error.php?error=500");
                exit;
            }

            $stmt->close();

        } else {
            header("Location: error.php?error=500");
            exit;
        }

    } else {
        header("Location: error.php?error=103");
        exit;
    }
} catch (Exception $e) {
    echo "Erro ao enviar o seu currículo: " . $e->getMessage();
}

$conn->close();

?>