<?php
session_start();

require_once("config/database.php");

$email = strtolower($_POST['email']);
$senha = $_POST['senha'];

try{
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
<<<<<<< HEAD
        $query = "SELECT u.id, u.nome, u.sobrenome, u.email, u.senha, t.id AS tipo FROM tbl_usuario u INNER JOIN tbl_tipo_usuario t ON u.tipo_usuario = t.id WHERE u.email = ?";
=======
        $query = "SELECT 
                    u.id, 
                    u.nome, 
                    u.sobrenome, 
                    u.data_nascimento, 
                    u.email, 
                    u.senha, 
                    t.id AS tipo 
                FROM tbl_usuario u 
                INNER JOIN tbl_tipo_usuario t 
                    ON u.tipo_usuario = t.id WHERE u.email = ?";

>>>>>>> fd767cd9714718c09127451c1d823fc847f8a26d
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        if($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $senhaHash = hash('sha256', $senha);

                if($senhaHash === $row['senha']) {

                    $_SESSION['login'] = True;
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['nome'] = $row['nome'];
                    $_SESSION['data_nascimento'] = $row['data_nascimento'];
                    $_SESSION['sobrenome'] = $row['sobrenome'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['tipo_usuario'] = $row['tipo'];

                } else {
                    die("Usuário ou senha incorretos!");
                }
            }

        } else {
            die("Usuário ou senha incorretos!");
        }

    }
} catch (Exception $e) {
    echo "Erro no login, contate o suporte." . $e;
}

$conn->close();
header("Location: index.php")
?>
