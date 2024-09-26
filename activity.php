<?php
require 'auth_check.php';
require 'db.php';

$user_id = $_SESSION['user_id'];
$activity = null;

// Se tiver um ID na URL, edita a atividade
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM activities WHERE id = :id AND user_id = :user_id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $activity = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $status = $_POST['status'];

    if ($activity) {
        // Atualiza atividade existente
        $stmt = $pdo->prepare("UPDATE activities SET name = :name, description = :description, start_time = :start_time, end_time = :end_time, status = :status WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $activity['id']);
        $stmt->bindParam(':user_id', $user_id);
    } else {
        // Cria nova atividade
        $stmt = $pdo->prepare("INSERT INTO activities (user_id, name, description, start_time, end_time, status) VALUES (:user_id, :name, :description, :start_time, :end_time, :status)");
        $stmt->bindParam(':user_id', $user_id);
    }

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':start_time', $start_time);
    $stmt->bindParam(':end_time', $end_time);
    $stmt->bindParam(':status', $status);

    if ($stmt->execute()) {
        header('Location: dashboard.php');
    } else {
        echo "Erro ao salvar a atividade.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title><?= $activity ? 'Editar Atividade' : 'Criar Atividade' ?></title>
</head>
<body>
    <div class="container mt-5">
        <h2><?= $activity ? 'Editar Atividade' : 'Criar Atividade' ?></h2>

        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" name="name" class="form-control" id="name" value="<?= $activity['name'] ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <textarea name="description" class="form-control" id="description" required><?= $activity['description'] ?? '' ?></textarea>
            </div>
            <div class="mb-3">
                <label for="start_time" class="form-label">Data e Hora de Início</label>
                <input type="datetime-local" name="start_time" class="form-control" id="start_time" value="<?= $activity['start_time'] ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <label for="end_time" class="form-label">Data e Hora de Término</label>
                <input type="datetime-local" name="end_time" class="form-control" id="end_time" value="<?= $activity['end_time'] ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-select" id="status">
                    <option value="pendente" <?= ($activity['status'] ?? '') === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                    <option value="concluída" <?= ($activity['status'] ?? '') === 'concluída' ? 'selected' : '' ?>>Concluída</option>
                    <option value="cancelada" <?= ($activity['status'] ?? '') === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><?= $activity ? 'Salvar Alterações' : 'Criar Atividade' ?></button>
        </form>
    </div>
</body>
</html>
