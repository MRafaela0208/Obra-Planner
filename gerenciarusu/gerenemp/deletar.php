<?php
include('../../../includes/db_connect.php'); // Conexão com o banco

if (isset($_GET['delete'])) {
    $empresa_id = $_GET['delete'];

    $query_fiscais = "DELETE FROM fiscais WHERE empresa_id = ?";
    $stmt_fiscais = $conn->prepare($query_fiscais);
    $stmt_fiscais->bind_param('i', $empresa_id);
    $stmt_fiscais->execute();

    $query_empresa = "DELETE FROM empresas WHERE empresa_id = ?";
    $stmt_empresa = $conn->prepare($query_empresa);
    $stmt_empresa->bind_param('i', $empresa_id);

    if ($stmt_empresa->execute()) {
        header("Location: gerenemp.php?msg=delete_success");
    } else {
        echo "Erro ao deletar empresa.";
    }
}
?>
