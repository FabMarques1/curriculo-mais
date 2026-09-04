<?php
session_start();

require_once("config/database.php");

if(isset($_SESSION['login'])) {
    header("Location: index.php");
}

$email = strtolower($_POST['email']);
$senha = $_POST['senha'];

try{
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $query = "SELECT id, nome, sobrenome, email, senha, id_cidade FROM tbl_usuario WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        if($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $senhaHash = hash('sha256', $senha);

                if($senhaHash === $row['senha']) {

                    $_SESSION['logado'] = True;
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['nome'] = $row['nome'];
                    $_SESSION['sobrenome'] = $row['sobrenome'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['cidade'] = $row['id_cidade'];

                } else {
                    die("Usuário ou senha incorretos!");
                }
            }

        } else {
            die("Usuário não encontrado.");
        }

    } catch (Exception $e) {
        echo "Erro no login." . $e->getMessage();
    }
}

$conn->close();
header("Location: index.php");

?>