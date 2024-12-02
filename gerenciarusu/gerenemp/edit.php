<?php
include('../../../includes/db_connect.php');

if (isset($_GET['edit'])) {
    $empresa_id = $_GET['edit'];

    $query = "SELECT * FROM empresas WHERE empresa_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $empresa_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $empresa = $result->fetch_assoc();
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $cnpj = $_POST['cnpj'];
        $responsavel = $_POST['responsavel'];
        $status = $_POST['status'];
        $endereco = $_POST['endereco']; 

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $logo_temp = $_FILES['logo']['tmp_name'];
            $logo_name = $_FILES['logo']['name'];
            $logo_path = 'uploads/' . basename($logo_name); 
            move_uploaded_file($logo_temp, $logo_path); 

            $query = "UPDATE empresas SET nome=?, email=?, telefone=?, cnpj=?, responsavel=?, status=?, logo=?, endereco=? WHERE empresa_id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ssssssssi', $nome, $email, $telefone, $cnpj, $responsavel, $status, $logo_path, $endereco, $empresa_id);
        } else {
            $query = "UPDATE empresas SET nome=?, email=?, telefone=?, cnpj=?, responsavel=?, status=?, endereco=? WHERE empresa_id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sssssssi', $nome, $email, $telefone, $cnpj, $responsavel, $status, $endereco, $empresa_id);
        }

        if ($stmt->execute()) {
            header("Location: gerenemp.php?msg=edit_success");
        } else {
            echo "Erro ao editar empresa.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ObraPlanner</title>
    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<style>
    h1 {
        font-size: 28px;
        color: #01306e;
        margin-bottom: 30px;
        border-bottom: 2px solid #01306e;
        padding-bottom: 10px;
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

    label {
        display: block;
        font-size: 16px;
        font-weight: bold;
        color: #074470;
        margin-bottom: 5px;
        margin-top: 5px;
    }

    input[type="text"],
    input[type="email"],
    textarea,
    input[type="file"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        box-sizing: border-box;
        transition: all 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    textarea:focus,
    input[type="file"]:focus {
        border-color: #01306e;
        outline: none;
    }

    .file-upload {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 15px;
        font-size: 16px;
        cursor: pointer;
        margin-top: 20px;
        padding: 10px 15px;
        border-radius: 8px;
    }

    .file-upload input[type="file"] {
        display: none;
    }

    .file-upload i {
        font-size: 20px;
    }

    .file-status {
        color: green;
        font-size: 14px;
        margin-left: 10px;
    }

    textarea {
        height: 120px;
        resize: vertical;
    }

    .input-group {
        display: flex;
        gap: 20px;
    }

    .input-group input[type="text"] {
        width: 48%; 
    }

    .input-group input[type="email"],
    .input-group input[type="text"]:not(.phone), 
    .input-group input[type="file"],
    .input-group textarea {
        width: 100%; 
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
    <header>
        <div class="logo">
            <img src="../../../imgs/obraplanner7.png" alt="Logo do ObraPlanner" style="max-height: 50px;">
        </div>
    </header>
    
    <nav class="sidebar">
        <ul>
            <li><a href="../../dash.php"><i class="bx bx-home"></i> Home</a></li>
            <li><a href="../geren.php"><i class="bx bx-task"></i> Gerenciar Usuários</a></li>
            <li><a href="../../gerenciarproj/proj.php"><i class="bx bx-task"></i> Gerenciar Projetos</a></li>
            <li><a href="../../config/config.php"><i class="bx bx-cog"></i> Configurações</a></li>
            <li><a href="../../../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
        </ul>
    </nav>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 30px;">
    <h1>Editar Empresa</h1>
    <div class="form-container">
    <form method="POST" enctype="multipart/form-data">
        <label for="">Nome:</label><input type="text" name="nome" value="<?php echo $empresa['nome']; ?>"><br>
        <label for="">Email:</label><input type="email" name="email" value="<?php echo $empresa['email']; ?>"><br>

        <div class="input-group">
            <div>
                <label for="">Telefone:</label>
                <input type="text" name="telefone" value="<?php echo $empresa['telefone']; ?>"><br>
            </div>
            <div>
                <label for="">CNPJ:</label>
                <input type="text" name="cnpj" value="<?php echo $empresa['cnpj']; ?>"><br>
            </div>
            <label class="file-upload" id="imagem-upload">
            <i class='bx bx-upload'></i>
            <span>Logo:</span>
            <input type="file" name="logo" accept="image/*" onchange="handleFileUpload(this, 'imagem-status')">
            <div class="file-status" id="imagem-status"></div>
        </label><br>
        </div>

        <label for="">Responsável</label><input type="text" name="responsavel" value="<?php echo $empresa['responsavel']; ?>"><br>
        <label for="">Endereço</label><input type="text" name="endereco" value="<?php echo $empresa['endereco']; ?>"><br>
        <div class="button-container">
        <input type="submit" value="Atualizar Empresa" class="btnproj">
        <a href="gerenemp.php" class="btnproj">Cancelar</a>
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
