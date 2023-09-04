<?php
include '../includes/connection.php';
@session_start();
if (!isset($_SESSION['user']) || !isset($_SESSION['Admin'])) {
    header('Location: ../index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-user-circle"></i>
            <h3>Admin</h3>
        </div>
        <ul>
            <a href="../index.php">
                <li><i class="fa-solid fa-house"></i>Home</li>
            </a>
            <a href="admin-DGI.php">
                <li><i class="fa-solid fa-star-of-life"></i>DGI</li>
            </a>
            <a href="admin-CNSS.php">
                <li><i class="fa-solid fa-star-of-life"></i>CNSS</li>
            </a>
            <a href="admin-TC.php">
                <li><i class="fa-solid fa-star-of-life"></i>TC</li>
            </a>
            <a href="admin-users.php">
                <li><i class="fa-solid fa-users"></i>Users</li>
            </a>
            <a href="admin-society-manage.php">
                <li><i class="fa-solid fa-briefcase"></i>Sociétés</li>
            </a>
            <a href="../logout.php">
                <li class="logout"><i class="fa-solid fa-right-from-bracket"></i>Logout</li>
            </a>
        </ul>
    </div>
</body>

</html>