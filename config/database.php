<?php

$host = "host";
$user = "root";
$password = "senha";
$database = "curriculo_mais_db";

try{
    $conn = new MySQLi($host, $user, $password, $database);
    # Conexão bem-sucedida!
} catch (Exception $e) {
    die("Houve um problema na conexão com o banco de dados: " . $e->getMessage());
}

?>
