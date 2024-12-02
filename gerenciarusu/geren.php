<?php
include '../../includes/db_connect.php';

function getUsuarios($conn) {
    $sql = "SELECT usu_id, nome, email, tipo, data_cadastro FROM usuarios"; 
    $result = $conn->query($sql);
    
    $usuarios = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
    }
    return $usuarios;
}

$usuarios = getUsuarios($conn); 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ObraPlanner</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        th{
            text-align: center;
        }
        h4{
            color: #135545;
        }
        h3{
            color: #135545;
        }
        .btn-custom {
            border-radius: 20px; 
            transition: background-color 0.3s ease; 
            color: white;
            font-weight: 600;
            border: none;
        }
        .btn-custom:hover {
            background-color: #148799; 
            color: white; 
        }
        .btn-danger {
            background-color: white;
            color:#dc3545; 
        }
        .btn-danger:hover {
            background-color: #dc3545;
            color:white; 
        }
        .btn-success{
            background-color: white;
            color: #28a745;
        }
        .btn-success:hover{
            background-color:#28a745; 
        }
        .btn-secondary:hover{
            background-color: #555;
        }
    </style>
</head>
<body>
<main>
    <header>
        <div class="logo">
            <img src="../../imgs/obraplanner7.png" alt="Logo do ObraPlanner" style="max-height: 50px;">
        </div>
    </header>
    <nav class="sidebar">
        <ul>
            <li><a href="../dash.php"><i class="bx bx-home"></i> Home</a></li>
            <li><a href="geren.php"><i class="bx bx-task"></i> Gerenciar Usuários</a></li>
            <li><a href="../gerenciarproj/proj.php"><i class="bx bx-task"></i> Gerenciar Projetos</a></li>
            <li><a href="../config/config.php"><i class="bx bx-cog"></i> Configurações</a></li>
            <li><a href="../../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
        </ul>
    </nav>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 30px;">
        <h2>Gerenciar Usuários</h2>
        <div class="btn-group mb-3" role="group">
            <a href="gerenemp/gerenemp.php" class="btn btn-custom btn-info">Gerenciar Empresas</a>
            <a href="gerenfis/gerenfis.php" class="btn btn-custom btn-info">Gerenciar Fiscais</a>
            <a href="gerenfunc/gerenfunc.php" class="btn btn-custom btn-info">Gerenciar Funcionários</a>
            <a href="relatorio.php" class="btn btn-custom btn-secondary">Gerar Relatório</a>
        </div>
        <h4>Novos Usuários Cadastrados</h4>
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Dt/Cadastro</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td></td>
                        <td style="text-align: center;"><?php echo $usuario['usu_id']; ?></td>
                        <td ><?php echo $usuario['nome']; ?></td>
                        <td ><?php echo $usuario['email']; ?></td>
                        <td ><?php echo $usuario['tipo']; ?></td>
                        <td style="text-align: center;"><?php echo $usuario['data_cadastro'];?></td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
</main>
</body>
</html>

<?php
$conn->close(); 
?>
