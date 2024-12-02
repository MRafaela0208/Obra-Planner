<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../../includes/db_connect.php';

session_start();

if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['UsuarioID'];

$query = "SELECT * FROM admin WHERE admin_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
} else {
    error_log("Admin não encontrado: ID = " . htmlspecialchars($admin_id));
    echo "Administrador não encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>ObraPlanner</title>
    <style>
          .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 800px;
            width: 100%;
        }
        h1 {
            color: #135545;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
            display: flex; 
            align-items: center; 
        }
        .form-group label {
            font-weight: bold;
            color: #074470;
            margin-right: 10px; 
            white-space: nowrap;
        }
        .editable {
            cursor: pointer;
            color: #000; 
            border-bottom: none; 
        }
        .form-group img {
            max-height: 110px;
            border-radius: 50%;
            width: 110px;
            margin-bottom: 10px;
            display: block; 
            margin-left: auto; 
            margin-right: auto; 
        }
        .btnproj {
            background-color: #fff;
            color: #074470;
            border: solid 1px #074470;
            padding: 0.5rem 1.5rem;
            font-size: 1rem;
            border-radius: 20px;
            text-decoration: none;
            display: inline-block;
            text-align: center; 
        }
        .btnproj:hover {
            background-color: #074470;
            color: #fff;
            border: solid 1px #074470;      
        }
        .btn-group {
            text-align: center;
        }
        .form-group input[type="file"] {
            display: none;
        }
    </style>
</head>
<body>
<header>
    <div class="logo" style="text-align: center;">
        <img src="../../imgs/obraplanner7.png" alt="Logo do ObraPlanner">
    </div>
</header>
<nav class="sidebar">
    <ul>
        <li><a href="../dash.php"><i class="bx bx-home"></i> Home</a></li>
        <li><a href="../gerenciarusu/geren.php"><i class="bx bx-task"></i> Gerenciar Usuários</a></li>
        <li><a href="../gerenciarproj/proj.php"><i class="bx bx-task"></i> Gerenciar Projetos</a></li>
        <li><a href="config.php"><i class="bx bx-cog"></i> Configurações</a></li>
        <li><a href="../../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
    </ul>
</nav>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 50px;">
    <div class="container">
        <h1>Configurações do Administrador</h1>

        <div class="form-group" style="display: flex; justify-content: center; align-items: center; text-align: center;">
    <?php if (!empty($admin['foto'])): ?>
        <label class="custom-file-upload">
            <img id="imgPreview" src="../../uploads/fotos/<?php echo htmlspecialchars($admin['foto']); ?>" alt="" onclick="document.getElementById('foto-input').click();" style="width: 110px; height: 100px; object-fit: cover;">
            <input type="file" id="foto" name="foto" accept="image/*" onchange="previewImage(event)">
        </label>
    <?php else: ?>
        <label class="custom-file-upload">
            <img id="imgPreview" src="../../imgs/do-utilizador (1).png" alt="Imagem prévia" style="width: 110px; height: 100px; object-fit: cover;">
            <input type="file" id="foto" name="foto" accept="image/*" onchange="previewImage(event)">
        </label>
    <?php endif; ?>
</div>
        <div class="form-group">
            <label>Nome:</label>
            <span class="editable" data-field="nome" onclick="enableEdit(this)"><?php echo htmlspecialchars($admin['nome'] ?? 'N/A'); ?></span>
        </div>

        <div class="form-group">
            <label>E-mail:</label>
            <span class="editable" data-field="email" onclick="enableEdit(this)"><?php echo htmlspecialchars($admin['email'] ?? 'N/A'); ?></span>
        </div>

        <div class="form-group">
            <label>Senha:</label>
            <span class="editable" data-field="senha" onclick="enableEdit(this)">******</span>
        </div>

        <div class="btn-group">
            <a href="config2.php" class="btnproj">Editar</a>
        </div>
    </div>
</main>
</body>
</html>
