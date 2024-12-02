<?php
session_start();
if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php");
    exit();
}

$func_id = $_GET['id'];

try {
    $conn = new PDO('mysql:host=localhost;dbname=obra_planner', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sqlDeletePresenca = "DELETE FROM presenca WHERE func_id = :func_id";
    $stmtDeletePresenca = $conn->prepare($sqlDeletePresenca);
    $stmtDeletePresenca->bindParam(':func_id', $func_id, PDO::PARAM_INT);
    $stmtDeletePresenca->execute();

    $sqlUpdateProjetos = "UPDATE projetos SET func_id = NULL WHERE func_id = :func_id";
    $stmtUpdateProjetos = $conn->prepare($sqlUpdateProjetos);
    $stmtUpdateProjetos->bindParam(':func_id', $func_id, PDO::PARAM_INT);
    $stmtUpdateProjetos->execute();

    $sql = "DELETE FROM funcionarios WHERE func_id = :func_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':func_id', $func_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Funcionário deletado com sucesso!";
    } else {
        $_SESSION['message'] = "Erro ao deletar funcionário.";
    }

    header("Location: gerenfunc.php");
    exit();

} catch (PDOException $e) {
    $_SESSION['message'] = "Erro ao deletar funcionário: " . $e->getMessage();
    header("Location: gerenfunc.php");
    exit();
}

$conn = null;
?>
