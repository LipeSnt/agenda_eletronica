<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>

<div id="calendar"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'pt-br', 
        timeZone: 'local', 

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

     
        views: {
            dayGridMonth: {
                dayMaxEventRows: 4
            },
            timeGridWeek: {
                titleFormat: { year: 'numeric', month: 'numeric', day: 'numeric' },
                slotLabelFormat: { hour: '2-digit', minute: '2-digit', hour12: false }
            },
            timeGridDay: {
                titleFormat: { year: 'numeric', month: 'numeric', day: 'numeric' },
                slotLabelFormat: { hour: '2-digit', minute: '2-digit', hour12: false }
            }
        },

 
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false 
        },

        
        events: [
            <?php foreach ($activities as $activity): ?>
            {
                title: '<?= $activity['name'] ?>',
                start: '<?= $activity['start_time'] ?>',
                end: '<?= $activity['end_time'] ?>',
                description: '<?= $activity['description'] ?>'
            },
            <?php endforeach; ?>
        ]
    });

    calendar.render();
});
</script>

<?php
session_start();
require 'auth_check.php';
require 'db.php';




$user_id = $_SESSION['user_id'];


$stmt = $pdo->prepare("SELECT * FROM activities WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Painel de Controle</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .logo {
            width: 125px; 
            height: auto;
        }
        .header {
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: #ffffff; 
            border-bottom: 1px solid #e0e0e0; 
        }
    </style>
    <div class="header">
        <img src="assets/logo.png" alt="Logo" class="logo">
    </div>
</head>
<body>
    
    <div class="container mt-5">
        <h2>Painel de Controle</h2>
        <a href="activity.php" class="btn btn-success mb-3">Criar Nova Atividade</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Data de Início</th>
                    <th>Data de Término</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
    <?php foreach ($activities as $activity): ?>
    <tr>
        <td><?= $activity['name'] ?></td>
        <td><?= $activity['description'] ?></td>
        <td>
            <?= date('d/m/Y H:i', strtotime($activity['start_time'])) ?>
        </td>
        <td>
            <?= date('d/m/Y H:i', strtotime($activity['end_time'])) ?>
        </td>
        <td><?= $activity['status'] ?></td>
        <td>
            <a href="activity.php?id=<?= $activity['id'] ?>" class="btn btn-primary">Editar</a>
            <a href="delete_activity.php?id=<?= $activity['id'] ?>" class="btn btn-danger">Excluir</a>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>

        </table>
    </div>
</body>
</html>

