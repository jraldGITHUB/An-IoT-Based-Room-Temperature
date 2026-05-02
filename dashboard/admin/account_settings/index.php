<?php
session_start();
include "../../../db.php";

if(!isset($_SESSION['username'])){
    header("Location: ../../../login/index.php");
    exit();
}

if(isset($_POST['add_user'])){
    $username = trim($_POST['username']);
    $password = md5($_POST['password']);
    $role     = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();

    $admin_id = $_SESSION['user_id'];
    $action = "Create User";
    $details = "Created account: $username ($role)";
    $log = $conn->prepare("INSERT INTO activity_logs (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())");
    $log->bind_param("iss", $admin_id, $action, $details);
    $log->execute();

    header("Location: index.php");
    exit();
}

if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    if($id != $_SESSION['user_id']){
        $res = $conn->query("SELECT username, role FROM users WHERE id=$id");
        $user = $res->fetch_assoc();
        $conn->query("DELETE FROM users WHERE id=$id");

        $admin_id = $_SESSION['user_id'];
        $action = "Delete User";
        $details = "Deleted account: {$user['username']} ({$user['role']})";
        $log = $conn->prepare("INSERT INTO activity_logs (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())");
        $log->bind_param("iss", $admin_id, $action, $details);
        $log->execute();
    }
    header("Location: index.php");
    exit();
}

$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>IoT Room Monitor | Manage Accounts</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="style.css">

</head>
<body>

<nav class="navbar navbar-expand-lg shadow-sm">
<div class="container">
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
    <span class="navbar-toggler-icon"></span>
</button>
<a class="navbar-brand" href="../index.php">IoT Room Monitor</a>
<div class="collapse navbar-collapse" id="navMenu">
<ul class="navbar-nav ms-auto">
<li class="nav-item"><a class="nav-link" href="../index.php">Dashboard</a></li>
<li class="nav-item"><a class="nav-link" href="../logs_page/index.php">Logs</a></li>
<li class="nav-item"><a class="nav-link" href="../add_monitor_rooms/index.php">Manage Rooms</a></li>
<li class="nav-item"><a class="nav-link" href="../activity_logs/index.php">Activity Logs</a></li>
<li class="nav-item"><a class="nav-link active" href="#">Account Settings</a></li>
<li class="nav-item"><a class="nav-link" href="../../../login/logout.php">Logout</a></li>
</ul>
</div>
</div>
</nav>

<div class="page-wrapper">
<h1 class="page-title">Manage Accounts</h1>

<div class="row g-4">

<!-- CREATE ACCOUNT -->
<div class="col-lg-4">
<div class="panel">
<div class="panel-header"><h5>Create Account</h5></div>
<div class="panel-body">
<form method="POST">
<div class="mb-3">
    <label class="form-label">Username</label>
    <input type="text" name="username" class="form-control" placeholder="Enter username" required>
</div>
<div class="mb-3">
    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
</div>
<div class="mb-3">
    <label class="form-label">Role</label>
    <select name="role" class="form-select" required>
        <option value="">Select role</option>
        <option value="admin">Admin</option>
        <option value="manager">Manager</option>
        <option value="user">User</option>
    </select>
</div>
<button type="submit" name="add_user" class="btn-create">Create Account</button>
</form>
</div>
</div>
</div>

<!-- ACCOUNTS LIST -->
<div class="col-lg-8">
<div class="panel">
<div class="panel-header"><h5>All Accounts</h5></div>
<div class="table-responsive">
<table class="table">
<thead>
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Role</th>
    <th style="text-align:right">Action</th>
</tr>
</thead>
<tbody>
<?php while($row = $users->fetch_assoc()): ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td class="td-username"><?php echo htmlspecialchars($row['username']); ?></td>
<td>
    <span class="role-badge role-<?php echo $row['role']; ?>">
        <?php echo ucfirst($row['role']); ?>
    </span>
</td>
<td style="text-align:right">
    <?php if($row['id'] != $_SESSION['user_id']): ?>
    <a href="?delete=<?php echo $row['id']; ?>"
       class="btn-del"
       onclick="return confirm('Delete this account?')">Delete</a>
    <?php else: ?>
    <span class="current-user">Current user</span>
    <?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>
</div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>