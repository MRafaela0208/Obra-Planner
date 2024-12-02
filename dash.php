<?php
include '../includes/db_connect.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>ObraPlanner</title>
</head>
<body>
<header>
    <div class="logo">
        <img src="../imgs/obraplanner7.png" alt="Logo do ObraPlanner" style="max-height: 50px;">
    </div>
</header>
<nav class="sidebar">
    <ul>
        <li><a href="dash.php"><i class="bx bx-home"></i> Home</a></li>
        <li><a href="gerenciarusu/geren.php"><i class="bx bx-task"></i> Gerenciar Usuários</a></li>
        <li><a href="gerenciarproj/proj.php"><i class="bx bx-task"></i> Gerenciar Projetos</a></li>
        <li><a href="config/config.php"><i class="bx bx-cog"></i> Configurações</a></li>
        <li><a href="../index.php" class="btn-logout"><i class="bx bx-log-out"></i> Sair</a></li>
    </ul>
</nav>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left: 250px; padding-top: 30px;">
    <h2>Visão Geral do Sistema</h2>
    <div class="row">
        <?php
        $totalEmpresas = $conn->query("SELECT COUNT(*) AS total FROM empresas")->fetch_assoc()['total'];
        $projetosAtivos = $conn->query("SELECT COUNT(*) AS total FROM projetos")->fetch_assoc()['total'];
        $funcionariosRegistrados = $conn->query("SELECT COUNT(*) AS total FROM funcionarios")->fetch_assoc()['total'];
        $fiscaisRegistrados = $conn->query("SELECT COUNT(*) AS total FROM fiscais")->fetch_assoc()['total'];
        ?>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title"><a href="gerenciarusu/gerenemp/gerenemp.php" class="text-white" style="text-decoration: none;">Empresas</a></h5>
                    <p class="card-text" style="color:white;"><?php echo $totalEmpresas; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title"><a href="gerenciarproj/proj.php" class="text-white" style="text-decoration: none;">Projetos</a></h5>
                    <p class="card-text" style="color:white;"><?php echo $projetosAtivos; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title"><a href="gerenciarusu/gerenfunc/gerenfunc.php" class="text-white" style="text-decoration: none;">Funcionários</a></h5>
                    <p class="card-text" style="color:white;"><?php echo $funcionariosRegistrados; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title"><a href="gerenciarusu/gerenfis/gerenfis.php" class="text-white" style="text-decoration: none;">Fiscais</a></h5>
                    <p class="card-text" style="color:white;"><?php echo $fiscaisRegistrados; ?></p>
                </div>
            </div>
        </div>
    </div>
    <section class="reports">
        <h2>Relatórios Detalhados</h2>
        <table>
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>Projetos Ativos</th>
                    <th>Funcionários</th>
                    <th>Fiscais</th>
                    <th>Horas Trabalhadas</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $reportData = $conn->query("
                    SELECT 
                        e.nome AS empresa, 
                        COUNT(p.projeto_id) AS projetos, 
                        COUNT(f.func_id) AS funcionarios, 
                        COUNT(fs.fiscal_id) AS fiscais,
                        SUM(f.horas_trabalhadas) AS horas_trabalhadas 
                    FROM empresas e 
                    LEFT JOIN projetos p ON e.empresa_id = p.empresa_id 
                    LEFT JOIN funcionarios f ON e.empresa_id = f.empresa_id 
                    LEFT JOIN fiscais fs ON e.empresa_id = fs.empresa_id
                    GROUP BY e.empresa_id
                ");

                while ($row = $reportData->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['empresa']}</td>
                        <td>{$row['projetos']}</td>
                        <td>{$row['funcionarios']}</td>
                        <td>{$row['fiscais']}</td>
                        <td>{$row['horas_trabalhadas']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</main>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx1 = document.getElementById('projectsChart').getContext('2d');
    const projectsChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Projeto 1', 'Projeto 2', 'Projeto 3'],
            datasets: [{
                label: 'Horas Trabalhadas',
                data: [300, 200, 400],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const ctx2 = document.getElementById('attendanceChart').getContext('2d');
    const attendanceChart = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4'],
            datasets: [{
                label: 'Presença dos Funcionários',
                data: [80, 90, 70, 85],
                fill: false,
                borderColor: 'rgba(153, 102, 255, 1)',
                tension: 0.1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>
