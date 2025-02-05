<?php
include 'conecta_mysql.inc';

// Consulta os votos agrupados por opção
$sql = "SELECT voto, COUNT(*) as quantidade FROM votos GROUP BY voto ORDER BY quantidade DESC";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Votação</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
        table { margin: auto; border-collapse: collapse; width: 50%; }
        th, td { border: 1px solid black; padding: 10px; }
    </style>
</head>
<body>

    <h1>Resultados da Votação</h1>

    <table>
        <thead>
            <tr>
                <th>Opção</th>
                <th>Quantidade de Votos</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['voto']}</td>
                            <td>{$row['quantidade']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='2'>Nenhum voto registrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <br>
    <!-- <a href="index.php">Voltar</a> -->

</body>
</html>

<?php
// Fecha a conexão
$conexao->close();
?>
