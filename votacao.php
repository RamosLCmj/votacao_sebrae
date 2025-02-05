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
    echo "<p style='color: red; font-size: 18px; font-weight: bold; text-align: center; padding: 10px; background-color: #ffe6e6; border: 1px solid red; border-radius: 5px;'>Ops! Você só pode votar uma vez.</p>";;
    echo "<a href='index.html'>Tentar Novamente</a>";
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
        echo "<p style='color: green; font-size: 20px; font-weight: bold;'>✅ Voto realizado com sucesso, $nome!</p>";
        echo "<p style='font-size: 16px;'>Confira abaixo a programação do Empodera Mulher:</p>";

        // Exibe o cronograma do evento
        echo '
        <style>
            .cronograma {
                width: 100%;
                max-width: 600px;
                margin-top: 20px;
                border-collapse: collapse;
                font-family: Arial, sans-serif;
            }
            .cronograma th, .cronograma td {
                border: 1px solid #ddd;
                padding: 10px;
                text-align: center;
            }
            .cronograma th {
                background-color: #4CAF50;
                color: white;
                font-weight: bold;
            }
            .cronograma tr:nth-child(even) {
                background-color: #f2f2f2;
            }
        </style>

        <table class="cronograma">
            <tr>
                <th>Horário</th>
                <th>Atividade</th>
            </tr>
            <tr>
                <td>19:00 - 19:30</td>
                <td>Credenciamento</td>
            </tr>
            <tr>
                <td>19:30 - 20:00</td>
                <td>Abertura</td>
            </tr>
            <tr>
                <td>20:00 - 21:00</td>
                <td>Palestra: Empoderamento Feminino</td>
            </tr>
            <tr>
                <td>21:00 - 21:10</td>
                <td>Premiação da empreendedora homenageada</td>
            </tr>
            <tr>
                <td>21:10 - 21:40</td>
                <td>Bate papo de mulheres</td>
            </tr>
            <tr>
                <td>21:40 - 22:00</td>
                <td>Sorteios de brindes</td>
            </tr>
            <tr>
                <td>22:00 - 22:30</td>
                <td>Encerramento e Lanche</td>
            </tr>
        </table>
        ';

    } else {
        echo "<p style='color: red;'>❌ Erro ao registrar o voto. Tente novamente.</p>" . $stmt_insere->error;
    }

    // Fecha a declaração de inserção
    $stmt_insere->close();
}

// Fecha a declaração de verificação e a conexão
$stmt_verifica->close();
$conexao->close();
?>