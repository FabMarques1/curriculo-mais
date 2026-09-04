<?php

require_once("config/database.php");

try{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = trim($_POST['nome']);
        $sobrenome = trim($_POST['sobrenome']);
        $email = strtolower(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
        $senha = $_POST['senha'];
        $cidade = $_POST['cidade'];

        $query = "SELECT id FROM tbl_usuario WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        if ($result->num_rows > 0) {
            die("Este email já existe.");
        } else {
            $senhaHash = hash('sha256', $senha);

            $sql = "INSERT INTO tbl_usuario (nome, sobrenome, email, senha, id_cidade) VALUES
                    (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $nome, $sobrenome, $email, $senhaHash, $cidade);
            
            if ($stmt->execute()) {
                header("Location: index.php");
            } else {
                die("Erro ao cadastrar usuário.");
            }

            $stmt->close();
                   
        }
    }
} catch (Exception $e) {
    die("Houve um problema com o envio de informações: " . $e->getMessage());
}


?>