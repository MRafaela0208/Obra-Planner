<?php
include '../../includes/db_connect.php';

if (isset($_GET['id'])) {
    $projeto_id = $_GET['id'];

    $sql = "DELETE FROM projetos WHERE projeto_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $projeto_id);

    if ($stmt->execute()) {
        header("Location: proj.php");
        exit;
    } else {
        echo "Erro ao excluir: " . $stmt->error;
    }
} else {
    echo "ID do projeto não fornecido.";
}
?>
