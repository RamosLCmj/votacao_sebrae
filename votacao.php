<?php
include 'conecta_mysql.inc';

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$dataNascimento = $_POST['dataNascimento'];
$voto = $_POST['voto'];
$erro = 0;

// Verifica se o CPF já está cadastrado
$sql_verifica = "SELECT cpf FROM votos WHERE cpf = ?";
$stmt_verifica = $conexao->prepare($sql_verifica);

if ($stmt_verifica === false) {
    die("Erro na preparação da consulta de verificação: " . $conexao->error);
}

// Associa o parâmetro e executa a consulta
$stmt_verifica->bind_param("s", $cpf);
$stmt_verifica->execute();
$stmt_verifica->store_result();

// Se o CPF já existir, retorna uma mensagem de erro
if ($stmt_verifica->num_rows > 0) {
    echo "Erro: Este CPF já está cadastrado.";
    $erro = 1;
} else {
    // CPF não cadastrado, prossegue com a inserção
    $sql_insere = "INSERT INTO votos (nome, cpf, data_nascimento, voto) VALUES (?, ?, ?, ?)";
    $stmt_insere = $conexao->prepare($sql_insere);

    if ($stmt_insere === false) {
        die("Erro na preparação da consulta de inserção: " . $conexao->error);
    }

    // Associa os parâmetros e executa a consulta
    $stmt_insere->bind_param("ssss", $nome, $cpf, $dataNascimento, $voto);
    if ($stmt_insere->execute()) {
        echo "Usuário incluído com sucesso";
    } else {
        echo "Erro ao incluir usuário: " . $stmt_insere->error;
    }

    // Fecha a declaração de inserção
    $stmt_insere->close();
}

// Fecha a declaração de verificação e a conexão
$stmt_verifica->close();
$conexao->close();
?>