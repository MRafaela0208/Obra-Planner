<?php
include '../../includes/db_connect.php';

if (isset($_GET['id'])) {
    $etapa_id = $_GET['id'];

    $sql = "DELETE FROM etapas WHERE etapa_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $etapa_id);

    if ($stmt->execute()) {
        header("Location: verproj.php");
        exit;
    } else {
        echo "Erro ao excluir a etapa.";
    }
} else {
    echo "ID da etapa não fornecido.";
}
?>
