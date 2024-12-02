<?php
include '../../includes/db_connect.php';

if (isset($_GET['id'])) {
    $etapa_id = $_GET['id'];

    $sql = "SELECT * FROM etapas WHERE etapa_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $etapa_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $etapa = $result->fetch_assoc();
    } else {
        echo "Etapa não encontrada.";
        exit;
    }
} else {
    echo "ID da etapa não fornecido.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $observacoes = $_POST['observacoes'];
    $data_inicio = $_POST['data_inicio'];
    $data_termino = $_POST['data_termino'];
    $data_previa_inicio = $_POST['data_previa_inicio'];
    $data_previa_termino = $_POST['data_previa_termino'];

    $sql = "UPDATE etapas SET titulo = ?, descricao = ?, observacoes = ?, data_inicio = ?, data_termino = ?, data_previa_inicio = ?, data_previa_termino = ? WHERE etapa_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $titulo, $descricao, $observacoes, $data_inicio, $data_termino, $data_previa_inicio, $data_previa_termino, $etapa_id);

    if ($stmt->execute()) {
        header("Location: verproj.php");
        exit;
    } else {
        echo "Erro ao atualizar a etapa.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>ObraPlanner</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
        h2 {
            color: #135545;
            text-align: center;
            margin-top: 5px;
            font-size: 1.8rem;
        }
        .form-container {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            padding: 40px;
            border-radius: 12px;
            margin: 20px auto;
            width: 90%;
            max-width: 750px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        form label {
            font-weight: 600;
            color: #074470;
            display: block;
            margin-bottom: 5px;
        }

        form input[type="text"],
        form textarea,
        form input[type="date"] {
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 15px;
            width: 100%;
            margin-bottom: 15px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .form-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .date-container {
            flex: 1;
            margin: 0 10px;
        }

        label {
            margin-bottom: 5px;
        }

        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 25px;
            gap: 20px;
        }

        .btnproj {
            background-color: #074470;
            color: #ffffff;
            border: solid 1px #074470;
            padding: 0.6rem 1.2rem;
            font-size: 1rem;
            border-radius: 5px;
            transition: background-color 0.2s, color 0.2s;
            text-decoration: none;
            text-align: center;
        }

        .btnproj:hover {
            background-color: #ffffff;
            color: #074470;
            border: solid 1px #074470;
        }
    </style>
<body>
<header>
        <div class="logo">
            <img src="../../imgs/obraplanner7.png" alt="Logo do ObraPlanner" style="max-height: 50px;">
        </div>
    </header>
    <nav class="sidebar">
    <ul>
        <li><a href="../dash.php"><i class="bx bx-home"></i> Home</a></li>
        <li><a href="../gerenciarusu/geren.php"><i class="bx bx-task"></i> Gerenciar Usuários</a></li>
        <li><a href="proj.php"><i class="bx bx-task"></i> Gerenciar Projetos</a></li>
        <li><a href="../config/config.php"><i class="bx bx-cog"></i> Configurações</a></li>
        <li><a href="../../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
    </ul>
</nav>
<body>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 50px;">
        <h2>Editar Etapa</h2>
        <div class="form-container">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="etapa_id" value="<?php echo $etapa['etapa_id']; ?>">
                
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($etapa['titulo']); ?>" required>

                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" required><?php echo htmlspecialchars($etapa['descricao']); ?></textarea>

                <label for="observacoes">Observações</label>
                <textarea id="observacoes" name="observacoes"><?php echo htmlspecialchars($etapa['observacoes']); ?></textarea>

                <br>
                <div class="form-group">
                    <div class="date-container">
                        <label for="data_inicio">Data de Início</label>
                        <input type="date" id="data_inicio" name="data_inicio" value="<?php echo $etapa['data_inicio']; ?>" required>
                    </div>
                    <div class="date-container">
                        <label for="data_termino">Data de Término</label>
                        <input type="date" id="data_termino" name="data_termino" value="<?php echo $etapa['data_termino']; ?>" required>
                    </div>
                </div>
                <div class="form-group">
                <div class="date-container">
                        <label for="data_previa_inicio">Data Prévia de Início</label>
                        <input type="date" id="data_previa_inicio" name="data_previa_inicio" value="<?php echo $etapa['data_previa_inicio']; ?>">
                    </div>
                    <div class="date-container">
                        <label for="data_previa_termino">Data Prévia de Término</label>
                        <input type="date" id="data_previa_termino" name="data_previa_termino" value="<?php echo $etapa['data_previa_termino']; ?>">
                    </div>
                </div>

                <div class="button-container">
                    <button type="submit" class="btnproj">Atualizar Etapa</button>
                    <a href="verproj.php" class="btnproj">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
