<?php
include '../../includes/db_connect.php';

$sql = "SELECT p.projeto_id, p.titulo, p.descricao, p.status, p.imagem, p.documentacao, 
               p.data_inicio, p.data_termino, p.data_prev_ini, p.data_prev_ter, 
               f.nome AS fiscal_nome, e.nome AS empresa_nome 
        FROM projetos p
        LEFT JOIN fiscais f ON p.fiscal_id = f.fiscal_id
        LEFT JOIN empresas e ON p.empresa_id = e.empresa_id";
$result = $conn->query($sql);
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
<h2 style="display: flex; justify-content: space-between; align-items: center;">
        Projetos <div class="btn-group mb-3" role="group">
        <a href="verproj.php?" class="btn btn-custom btn-info">Ver Etapas</a>
        <a href="relatorio.php" class="btn btn-custom btn-secondary">Gerar Relatório</a>
        </div> </h2>
    <table class="table">
    <thead>
            <tr>
                <th>ID</th>
                <th>Empresa</th>
                <th>Título</th>
                <th>Descrição</th>
                <th>Img</th>
                <th>Doc</th>
                <th>Status</th>
                <th>Dt/Início</th>
                <th>Dt/Término</th>
                <th>Dt/Prev/Ini</th>
                <th>Dt/Prev/Ter</th>
                <th>Responsável</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['projeto_id']; ?></td>
                        <td style=" text-align: center;"><?php echo htmlspecialchars($row['empresa_nome']); ?></td>
                        <td style=" text-align: center;"><?php echo htmlspecialchars(string: $row['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($row['descricao']); ?></td>
                        <td>
                            <?php if (!empty($row['imagem'])): ?>
                                <img src="uploads/<?php echo $row['imagem']; ?>" alt="Imagem" style="width: 50px; height: auto;">
                            <?php else: ?>
                                <span>Sem Imagem</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($row['documentacao'])): ?>
                                <a href="uploads/<?php echo $row['documentacao']; ?>" target="_blank">Download</a>
                            <?php else: ?>
                                <span>Sem Documento</span>
                            <?php endif; ?>
                        </td>
                        <td style=" text-align: center;"><?php echo htmlspecialchars($row['status']); ?></td>
                        <td style=" text-align: center;"><?php echo date("d/m/Y", strtotime($row['data_inicio'])); ?></td>
                        <td style=" text-align: center;"><?php echo date("d/m/Y", strtotime($row['data_termino'])); ?></td>
                        <td style=" text-align: center;"><?php echo date("d/m/Y", strtotime($row['data_prev_ini'])); ?></td>
                        <td style=" text-align: center;"><?php echo date("d/m/Y", strtotime($row['data_prev_ter'])); ?></td>
                        <td style=" text-align: center;"><?php echo htmlspecialchars($row['fiscal_nome'] ?? 'N/A'); ?></td>
                        <td style=" text-align: center;">
                            <a href="editproj.php?id=<?php echo $row['projeto_id']; ?>" class="btn btn-success btn-sm btn-custom editBtn">Editar</a>
                            <a href="delproj.php?id=<?php echo $row['projeto_id']; ?>" class="btn btn-danger btn-sm btn-custom editBtn">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="13">Nenhum projeto encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>