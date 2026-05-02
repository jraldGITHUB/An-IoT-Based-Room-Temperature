<?php 
session_start();
include "../db.php";

$rooms = $conn->query("SELECT * FROM rooms");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register | IoT Room Monitor</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="left-panel">
    <h1>IoT Room<br>Monitoring System</h1>
    <p class="tagline">Create your account and start monitoring your rooms in real time.</p>

    <div class="feature">
        <strong>Monitor Multiple Rooms</strong>
        Select multiple rooms during registration and track them all at once.
    </div>
    <div class="feature">
        <strong>For Users Only</strong>
        This registration is for user accounts only. Admin and manager accounts are created by an administrator.
    </div>
</div>

<div class="right-panel">
<div class="login-card">

    <div class="brand">IoT Room Monitor</div>

    <h3>Create Account</h3>
    <p class="sub">Register to start monitoring your assigned rooms.</p>

    <?php if(isset($_GET['error'])): ?>
    <div class="alert-danger">Username already exists.</div>
    <?php endif; ?>

    <?php if(isset($_GET['success'])): ?>
    <div class="alert-success">Account created successfully. Redirecting…</div>
    <script>setTimeout(()=>window.location="../login/index.php",1000);</script>
    <?php endif; ?>

    <form action="register_process.php" method="POST">

    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Enter username" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Select Rooms</label>
        <select name="room_ids[]" class="form-control" multiple required>
        <?php while($row = $rooms->fetch_assoc()): ?>
        <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['room_name']); ?></option>
        <?php endwhile; ?>
        </select>
        <p class="select-hint">Hold Ctrl / Cmd to select multiple rooms.</p>
    </div>

    <button type="submit" class="btn-register">Create Account</button>

    </form>

    <div class="card-footer-link">
        <a href="../login/index.php">Already have an account? Sign in</a>
    </div>

</div>
</div>

</body>
</html>