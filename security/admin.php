<?php
// HEADER
include '../templates/header.php';

// Vérifier si l'utilisateur a le rôle "ROLE_ADMIN"
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $role_sql = "SELECT role FROM users WHERE id = :user_id";
    $role_stmt = $pdo->prepare($role_sql);
    $role_stmt->bindParam(':user_id', $user_id);
    $role_stmt->execute();

    if ($role_stmt->rowCount() > 0) {
        $row = $role_stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['role'] !== 'ROLE_ADMIN') {
            // Rediriger l'utilisateur s'il n'a pas le rôle "ROLE_ADMIN"
            header('Location: ../public/index.php');
            exit;
        }
    } else {
        // Rediriger l'utilisateur si son rôle n'est pas trouvé
        header('Location: ../public/index.php');
        exit;
    }
} else {
    // Rediriger l'utilisateur s'il n'est pas connecté
    header('Location: ../public/index.php');
    exit;
}

// Récupérer tous les utilisateurs
$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);

// Traitement des opérations CRUD
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $userId = $_GET['id'];

    if ($action === 'edit') {
        // Vérifier si le formulaire de mise à jour a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $role = $_POST['role'];

            // Mettre à jour les données de l'utilisateur dans la base de données
            $updateSql = "UPDATE users SET username = :username, role = :role WHERE id = :id";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->bindValue(':username', $username);
            $updateStmt->bindValue(':role', $role);
            $updateStmt->bindValue(':id', $userId);
            $updateStmt->execute();
            exit;
        }

        // Récupérer les informations de l'utilisateur à éditer
        $editSql = "SELECT * FROM users WHERE id = :id";
        $editStmt = $pdo->prepare($editSql);
        $editStmt->bindValue(':id', $userId);
        $editStmt->execute();
        $user = $editStmt->fetch(PDO::FETCH_ASSOC);

        // Afficher la section de modification de l'utilisateur
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS -->
    <link rel="stylesheet" href="../public/style.css">
    <title>Modifier l'utilisateur</title>
</head>

<body>
    <style>
    body {
        background: white;
    }

    form {
        margin-top: 20px;
    }

    label {
        display: block;
        margin-top: 10px;
    }

    input[type="text"],
    input[type="password"],
    select {
        width: 100%;
        padding: 5px;
    }

    input[type="submit"],
    .retour {
        margin-top: 10px;
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .retour {
        background-color: #b94646;
        padding: 10px;
        color: white;
    }
    </style>
    <div class="container">
        <h2>Modifier l'utilisateur</h2>
        <form method="POST" action="admin.php">
            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo $user['username']; ?>" required><br>
            <label for="role">Role:</label>
            <select name="role">
                <option value="ROLE_USER" <?php echo ($user['role'] === 'ROLE_USER') ? 'selected' : ''; ?>>ROLE_USER
                </option>
                <option value="ROLE_ADMIN" <?php echo ($user['role'] === 'ROLE_ADMIN') ? 'selected' : ''; ?>>ROLE_ADMIN
                </option>
            </select><br>
            <input type="submit" value="Mettre à jour">
            <a class="retour" href="admin.php">Retour</a>
        </form>

    </div>
</body>

</html>
<?php
    } elseif ($action === 'delete') {
        // Supprimer l'utilisateur de la base de données
        $deleteSql = "DELETE FROM users WHERE id = :id";
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->bindValue(':id', $userId);
        $deleteStmt->execute();

        // Rediriger vers la page d'administration après la suppression
        header('Location: admin.php');
        exit;
    }
} else {
    // Afficher la liste des utilisateurs
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS -->
    <link rel="stylesheet" href="../public/style.css">
    <title>Admin</title>
</head>

<body>
    <style>
    body {
        background: white;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid black;
        padding: 12px;
    }

    th {
        text-align: left;
    }

    h1 {
        text-align: center;
        margin-top: 20px;
    }

    a {
        margin-right: 10px;
        text-decoration: none;
        color: #4CAF50;
    }

    a:hover {
        color: #45a049;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    </style>
    <div>
        <h1>Liste des utilisateurs</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['role']; ?></td>
                <td>
                    <a href="admin.php?action=edit&id=<?php echo $row['id']; ?>">Edit</a>
                    <a href="admin.php?action=delete&id=<?php echo $row['id']; ?>">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>

</html>
<?php
}
?>