<?php
include '../../includes/db_connect.php';

if (isset($_GET['id'])) {
    $projeto_id = $_GET['id'];

    $sql = "SELECT * FROM projetos WHERE projeto_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $projeto_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $projeto = $result->fetch_assoc();
    } else {
        echo "Projeto não encontrado.";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $data_inicio = $_POST['data_inicio'];
        $data_termino = $_POST['data_termino'];
        $data_prev_ini = $_POST['data_prev_ini'];
        $data_prev_ter = $_POST['data_prev_ter'];

        $imagem = $projeto['imagem']; 
        $documentacao = $projeto['documentacao']; 

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $imagemDir = "uploads/";
            if (!is_dir($imagemDir)) mkdir($imagemDir, 0777, true);

            $imagemTmp = $_FILES['imagem']['tmp_name'];
            $imagemNome = basename($_FILES['imagem']['name']);
            $imagemCaminho = $imagemDir . $imagemNome;

            if (move_uploaded_file($imagemTmp, $imagemCaminho)) {
                $imagem = '' . $imagemNome;
            } else {
                echo "Erro ao fazer upload da imagem.";
                exit;
            }
        }

        if (isset($_FILES['documento']) && $_FILES['documento']['error'] === UPLOAD_ERR_OK) {
            $documentoDir = 'uploads/';
            if (!is_dir($documentoDir)) mkdir($documentoDir, 0777, true);

            $documentoTmp = $_FILES['documento']['tmp_name'];
            $documentoNome = basename($_FILES['documento']['name']);
            $documentoCaminho = $documentoDir . $documentoNome;

            if (move_uploaded_file($documentoTmp, $documentoCaminho)) {
                $documentacao = 'uploads/' . $documentoNome;
            } else {
                echo "Erro ao fazer upload do documento.";
                exit;
            }
        }

        $sql = "UPDATE projetos SET titulo = ?, descricao = ?, imagem = ?, documentacao = ?, data_inicio = ?, data_termino = ?, data_prev_ini = ?, data_prev_ter = ? WHERE projeto_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssi", $titulo, $descricao, $imagem, $documentacao, $data_inicio, $data_termino, $data_prev_ini, $data_prev_ter, $projeto_id);

        if ($stmt->execute()) {
            header("Location: proj.php");
            exit;
        } else {
            echo "Erro: " . $stmt->error;
        }
    }
} else {
    echo "ID do projeto não fornecido.";
    exit;
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
        .file-upload {
            display: flex;
            align-items: center;
            margin-top: 15px;
            cursor: pointer;
            color: #074470;
            gap: 10px; 
            position: relative;
        }
        .file-upload input[type="file"] {
            display: none; 
        }
        .file-status {
            color: green; 
            margin-left: 10px; 
        }
        .upload-container {
            display: flex;
            justify-content: space-between;
        }
        .upload-container label {
            flex: 1;
            margin: 0 10px;
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
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 30px;">
    <h2>Editar Projeto</h2>
    <div class="form-container">
        <form method="POST" action="editproj.php?id=<?php echo $projeto_id; ?>" enctype="multipart/form-data">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" value="<?php echo htmlspecialchars($projeto['titulo']); ?>" required>
            <label for="descricao">Descrição:</label>
            <textarea name="descricao" required><?php echo htmlspecialchars($projeto['descricao']); ?></textarea>
            <div class="upload-container">
                <label class="file-upload" id="imagem-upload">
                    <i class='bx bx-upload'></i>
                    <span>Imagem:</span>
                    <input type="file" name="imagem" accept="image/*" onchange="handleFileUpload(this, 'imagem-status')">
                    <div class="file-status" id="imagem-status"></div>
                </label>

                <label class="file-upload" id="documento-upload">
                    <i class='bx bx-upload'></i>
                    <span>Documentação:</span>
                    <input type="file" name="documento" accept="application/pdf" onchange="handleFileUpload(this, 'documento-status')">
                    <div class="file-status" id="documento-status"></div>
                </label>
            </div>
            <br>
            <div class="form-group">
                <div class="date-container">
                    <label for="data_inicio">Data de Início:</label>
                    <input type="date" name="data_inicio" value="<?php echo $projeto['data_inicio']; ?>">
                </div>
                <div class="date-container">
                    <label for="data_termino">Data de Término:</label>
                    <input type="date" name="data_termino" value="<?php echo $projeto['data_termino']; ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="date-container">
                    <label for="data_prev_ini">Data Prevista Início:</label>
                    <input type="date" name="data_prev_ini" value="<?php echo $projeto['data_prev_ini']; ?>">
                </div>
                <div class="date-container">
                    <label for="data_prev_ter">Data Prevista Término:</label>
                    <input type="date" name="data_prev_ter" value="<?php echo $projeto['data_prev_ter']; ?>">
                </div>
            </div>
            <div class="button-container">
                <button type="submit" class="btnproj">Atualizar Projeto</button>
                <a href="proj.php" class="btnproj">Cancelar</a>
            </div>
        </form>
    </div>
</main>
<script>
    function handleFileUpload(input, statusId) {
        const fileStatus = document.getElementById(statusId);
        if (input.files && input.files[0]) {
            fileStatus.textContent = `Arquivo selecionado: ${input.files[0].name}`;
        } else {
            fileStatus.textContent = "Nenhum arquivo selecionado.";
        }
    }
</script>
</body>
</html>