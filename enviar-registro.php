<?php

require_once("config/database.php");

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nome             = ucfirst(trim($_POST['nome']));
        $sobrenome        = ucfirst(trim($_POST['sobrenome']));
        $data_nascimento  = $_POST['data_nascimento'];
        $email            = strtolower(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
        $senha            = $_POST['senha'];
        $cep              = preg_replace('/\D/', '', $_POST['cep']);
        $cidade           = (int) $_POST['cidade'];
        $logradouro       = $_POST['logradouro'];
        $bairro           = $_POST['bairro'];
        $complemento      = $_POST['complemento'];


        // Verifica se o email já está cadastrado
        $query = "SELECT id FROM tbl_usuario WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            die("Este email já existe.");
        }


        // Confirma que a cidade selecionada existe na tbl_cidade
        $queryCidade = "SELECT id FROM tbl_cidade WHERE id = ?";
        $stmtCidade = $conn->prepare($queryCidade);
        $stmtCidade->bind_param("i", $cidade);
        $stmtCidade->execute();

        $resultCidade = $stmtCidade->get_result();
        $stmtCidade->close();

        if ($resultCidade->num_rows === 0) {
            die("Cidade inválida.");
        }

        $id_cidade = $resultCidade->fetch_assoc()['id'];


        $senhaHash = hash('sha256', $senha);


        // A partir daqui, os três inserts precisam acontecer juntos
        $conn->begin_transaction();

        // 1. Usuário
        $sqlUsuario = "INSERT INTO tbl_usuario (nome, sobrenome, data_nascimento, email, senha)
                       VALUES (?, ?, ?, ?, ?)";
        $stmtUsuario = $conn->prepare($sqlUsuario);
        $stmtUsuario->bind_param("sssss", $nome, $sobrenome, $data_nascimento, $email, $senhaHash);
        $stmtUsuario->execute();

        $id_usuario = $conn->insert_id;
        $stmtUsuario->close();

        // 2. Endereço
        $sqlEndereco = "INSERT INTO tbl_endereco (cep, logradouro, complemento, bairro, id_cidade)
                        VALUES (?, ?, ?, ?, ?)";
        $stmtEndereco = $conn->prepare($sqlEndereco);
        $stmtEndereco->bind_param("ssssi", $cep, $logradouro, $complemento, $bairro, $id_cidade);
        $stmtEndereco->execute();

        $id_endereco = $conn->insert_id;
        $stmtEndereco->close();

        // 3. Relação usuário <-> endereço
        $sqlRelacao = "INSERT INTO tbl_usuario_has_tbl_endereco (id_usuario, id_endereco)
                       VALUES (?, ?)";
        $stmtRelacao = $conn->prepare($sqlRelacao);
        $stmtRelacao->bind_param("ii", $id_usuario, $id_endereco);
        $stmtRelacao->execute();
        $stmtRelacao->close();

        $conn->commit();

        header("Location: index.php");
        exit;

    }
} catch (Exception $e) {

    $conn->rollback();

    die("Erro ao registrar, contate o suporte.");
}