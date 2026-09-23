<?php

$url = "https://viacep.com.br/ws/12802520/json";
$response = file_get_contents($url);
$data = json_decode($response, true);

if ($data == NULL) {
    die("Erro ao decodificar JSON");
}

echo "CEP: " . $data['cep'] .
    "<br>CIDADE: " . $data['localidade'] .
    "<br>ESTADO: " . $data['uf'];

?>