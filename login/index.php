<?php 
session_start();
include "../db.php";

$error = "";

if(isset($_POST['login']))
{
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
    $result = mysqli_query($conn,$sql);

    if($result && mysqli_num_rows($result) == 1)
    {
        $row = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];

        if($row['role'] == 'admin')
            header("Location: ../dashboard/admin/index.php");
        elseif($row['role'] == 'manager')
            header("Location: ../dashboard/manager/index.php");
        elseif($row['role'] == 'user')
            header("Location: ../dashboard/user/index.php");

        exit();
    }
    else
    {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | IoT Room Monitor</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="left-panel">
    <h1>IoT Room<br>Monitoring System</h1>
    <p class="tagline">Real-time monitoring of room temperature, humidity, and environmental conditions using IoT sensors.</p>

    <div class="feature">
        <strong>Temperature Monitoring</strong>
        Track real-time room temperature.
    </div>
    <div class="feature">
        <strong>Humidity Monitoring</strong>
        Monitor humidity levels using DHT11 sensor.
    </div>
    <div class="feature">
        <strong>Real-Time Alerts</strong>
        Get notifications when conditions exceed limits.
    </div>

    <div class="test-accounts">
        <strong>Test Accounts</strong>
        <p>user@gmail.com / user123</p>
        <p>manager@gmail.com / manager123</p>
        <p>admin@gmail.com / admin123</p>
    </div>
</div>

<div class="right-panel">
<div class="login-card">

    <div class="brand">IoT Room Monitor</div>

    <h3>Welcome Back</h3>
    <p class="sub">Sign in to access your monitoring dashboard.</p>

    <?php if(!empty($error)): ?>
    <div class="alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Enter username" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
    </div>
    <button type="submit" name="login" class="btn-login">Sign In</button>
    </form>

    <div class="card-footer-link">
        <a href="../register/register.php">Don't have an account? Register</a>
    </div>

</div>
</div>

</body>
</html>