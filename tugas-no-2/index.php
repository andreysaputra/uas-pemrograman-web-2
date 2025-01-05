<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 10px; text-align: left; }
        form { margin-top: 20px; }
        .btn { padding: 5px 10px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>To-Do List</h1>

    <!-- Tambahkan Tugas -->
    <form action="add.php" method="POST">
        <input type="text" name="task" placeholder="Tugas baru..." required>
        <button type="submit" class="btn">Tambah</button>
    </form>

    <!-- Daftar Tugas -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tugas</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM todo_list");
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($tasks as $task) {
                echo "<tr>
                        <td>{$task['id']}</td>
                        <td>{$task['task']}</td>
                        <td>{$task['status']}</td>
                        <td>
                            <a href='edit.php?id={$task['id']}'>Edit</a> | 
                            <a href='delete.php?id={$task['id']}'>Hapus</a>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
