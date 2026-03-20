<?php 
session_start();
include "../db.php";

// FETCH ROOMS
$rooms = $conn->query("SELECT * FROM rooms");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register | IoT Room Monitor</title>

<link rel="stylesheet" href="../login/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
select[multiple]{
    height: 150px;
}
</style>

</head>

<body>

<div class="left-panel">
<h1>IoT Room Monitoring System</h1>
<p>Select one or more rooms to monitor.</p>
</div>

<div class="right-panel">
<div class="login-card">

<h3>Create Account</h3>

<?php if(isset($_GET['error'])): ?>
<div class="alert alert-danger">Username already exists</div>
<?php endif; ?>

<?php if(isset($_GET['success'])): ?>
<div class="alert alert-success">Account created successfully</div>
<script>
setTimeout(()=>window.location="../login/index.php",1000);
</script>
<?php endif; ?>

<form action="register_process.php" method="POST">

<div class="mb-3">
<label>Username</label>
<input type="text" name="username" class="form-control" required>
</div>

<div class="mb-3">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<!-- MULTIPLE ROOM SELECT -->
<div class="mb-3">
<label>Select Rooms</label>
<select name="room_ids[]" class="form-control" multiple required>
<?php while($row = $rooms->fetch_assoc()): ?>
<option value="<?= $row['id']; ?>">
<?= $row['room_name']; ?>
</option>
<?php endwhile; ?>
</select>
<small class="text-muted">Hold CTRL (Windows) or CMD (Mac) to select multiple</small>
</div>

<button class="btn btn-primary w-100">Register</button>

</form>

<div class="text-center mt-3">
<a href="../login/index.php">Already have an account? Login</a>
</div>

</div>
</div>

</body>
</html>