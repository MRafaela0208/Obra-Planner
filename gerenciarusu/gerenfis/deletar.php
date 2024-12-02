<?php
session_start();
include('../../../includes/db_connect.php');

if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['delete'])) {
    $fiscal_id = $_GET['delete'];

    try {
        mysqli_begin_transaction($conn);

        $sqlDeleteProjetos = "DELETE FROM projetos WHERE fiscal_id = ?";
        $stmtDeleteProjetos = mysqli_prepare($conn, $sqlDeleteProjetos);
        mysqli_stmt_bind_param($stmtDeleteProjetos, 'i', $fiscal_id);
        mysqli_stmt_execute($stmtDeleteProjetos);

        $sqlDeleteFiscal = "DELETE FROM fiscais WHERE fiscal_id = ?";
        $stmtDeleteFiscal = mysqli_prepare($conn, $sqlDeleteFiscal);
        mysqli_stmt_bind_param($stmtDeleteFiscal, 'i', $fiscal_id);
        mysqli_stmt_execute($stmtDeleteFiscal);

        mysqli_commit($conn);
        $_SESSION['message'] = "Fiscal deletado com sucesso!";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['message'] = "Erro ao deletar fiscal: " . $e->getMessage();
    }

    header("Location: gerenfis.php");
    exit();
}
?>
